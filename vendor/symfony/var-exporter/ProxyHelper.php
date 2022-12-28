<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ConfigTransformer202212\Symfony\Component\VarExporter;

use ConfigTransformer202212\Symfony\Component\VarExporter\Exception\LogicException;
use ConfigTransformer202212\Symfony\Component\VarExporter\Internal\Hydrator;
use ConfigTransformer202212\Symfony\Component\VarExporter\Internal\LazyObjectRegistry;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
final class ProxyHelper
{
    /**
     * Helps generate lazy-loading ghost objects.
     *
     * @throws LogicException When the class is incompatible with ghost objects
     */
    public static function generateLazyGhost(\ReflectionClass $class) : string
    {
        if (\PHP_VERSION_ID >= 80200 && $class->isReadOnly()) {
            throw new LogicException(\sprintf('Cannot generate lazy ghost: class "%s" is read-only.', $class->name));
        }
        if ($class->isFinal()) {
            throw new LogicException(\sprintf('Cannot generate lazy ghost: class "%s" is final.', $class->name));
        }
        if ($class->isInterface() || $class->isAbstract()) {
            throw new LogicException(\sprintf('Cannot generate lazy ghost: "%s" is not a concrete class.', $class->name));
        }
        if (\stdClass::class !== $class->name && $class->isInternal()) {
            throw new LogicException(\sprintf('Cannot generate lazy ghost: class "%s" is internal.', $class->name));
        }
        if ($class->hasMethod('__get') && 'mixed' !== (self::exportType($class->getMethod('__get')) ?? 'mixed')) {
            throw new LogicException(\sprintf('Cannot generate lazy ghost: return type of method "%s::__get()" should be "mixed".', $class->name));
        }
        static $traitMethods;
        $traitMethods = $traitMethods ?? (new \ReflectionClass(LazyGhostTrait::class))->getMethods();
        foreach ($traitMethods as $method) {
            if ($class->hasMethod($method->name) && $class->getMethod($method->name)->isFinal()) {
                throw new LogicException(\sprintf('Cannot generate lazy ghost: method "%s::%s()" is final.', $class->name, $method->name));
            }
        }
        $parent = $class;
        while ($parent = $parent->getParentClass()) {
            if (\stdClass::class !== $parent->name && $parent->isInternal()) {
                throw new LogicException(\sprintf('Cannot generate lazy ghost: class "%s" extends "%s" which is internal.', $class->name, $parent->name));
            }
        }
        $propertyScopes = self::exportPropertyScopes($class->name);
        return <<<EOPHP
 extends \\{$class->name} implements \\Symfony\\Component\\VarExporter\\LazyObjectInterface
{
    use \\Symfony\\Component\\VarExporter\\LazyGhostTrait;

    private const LAZY_OBJECT_PROPERTY_SCOPES = {$propertyScopes};
}

// Help opcache.preload discover always-needed symbols
class_exists(\\Symfony\\Component\\VarExporter\\Internal\\Hydrator::class);
class_exists(\\Symfony\\Component\\VarExporter\\Internal\\LazyObjectRegistry::class);
class_exists(\\Symfony\\Component\\VarExporter\\Internal\\LazyObjectState::class);

EOPHP;
    }
    /**
     * Helps generate lazy-loading virtual proxies.
     *
     * @param \ReflectionClass[] $interfaces
     *
     * @throws LogicException When the class is incompatible with virtual proxies
     */
    public static function generateLazyProxy(?\ReflectionClass $class, array $interfaces = []) : string
    {
        if (!\class_exists((($class2 = $class) ? $class2->name : null) ?? \stdClass::class, \false)) {
            throw new LogicException(\sprintf('Cannot generate lazy proxy: "%s" is not a class.', $class->name));
        }
        if (($class2 = $class) ? $class2->isFinal() : null) {
            throw new LogicException(\sprintf('Cannot generate lazy proxy: class "%s" is final.', $class->name));
        }
        if (\PHP_VERSION_ID >= 80200 && (($class2 = $class) ? $class2->isReadOnly() : null)) {
            throw new LogicException(\sprintf('Cannot generate lazy proxy: class "%s" is read-only.', $class->name));
        }
        $methodReflectors = [(($class2 = $class) ? $class2->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED) : null) ?? []];
        foreach ($interfaces as $interface) {
            if (!$interface->isInterface()) {
                throw new LogicException(\sprintf('Cannot generate lazy proxy: "%s" is not an interface.', $interface->name));
            }
            $methodReflectors[] = $interface->getMethods();
        }
        $methodReflectors = \array_merge(...$methodReflectors);
        $extendsInternalClass = \false;
        if ($parent = $class) {
            do {
                $extendsInternalClass = \stdClass::class !== $parent->name && $parent->isInternal();
            } while (!$extendsInternalClass && ($parent = $parent->getParentClass()));
        }
        $methodsHaveToBeProxied = $extendsInternalClass;
        $methods = [];
        foreach ($methodReflectors as $method) {
            if ('__get' !== \strtolower($method->name) || 'mixed' === ($type = self::exportType($method) ?? 'mixed')) {
                continue;
            }
            $methodsHaveToBeProxied = \true;
            $trait = new \ReflectionMethod(LazyProxyTrait::class, '__get');
            $body = \array_slice(\file($trait->getFileName()), $trait->getStartLine() - 1, $trait->getEndLine() - $trait->getStartLine());
            $body[0] = \str_replace('): mixed', '): ' . $type, $body[0]);
            $methods['__get'] = \strtr(\implode('', $body) . '    }', ['Hydrator' => '\\' . Hydrator::class, 'Registry' => '\\' . LazyObjectRegistry::class]);
            break;
        }
        foreach ($methodReflectors as $method) {
            if ($method->isStatic() && !$method->isAbstract() || isset($methods[$lcName = \strtolower($method->name)])) {
                continue;
            }
            if ($method->isFinal()) {
                if ($extendsInternalClass || $methodsHaveToBeProxied || \method_exists(LazyProxyTrait::class, $method->name)) {
                    throw new LogicException(\sprintf('Cannot generate lazy proxy: method "%s::%s()" is final.', $class->name, $method->name));
                }
                continue;
            }
            if (\method_exists(LazyProxyTrait::class, $method->name) || $method->isProtected() && !$method->isAbstract()) {
                continue;
            }
            $signature = self::exportSignature($method);
            $parentCall = $method->isAbstract() ? "throw new \\BadMethodCallException('Cannot forward abstract method \"{$method->class}::{$method->name}()\".')" : "parent::{$method->name}(...\\func_get_args())";
            if ($method->isStatic()) {
                $body = "        {$parentCall};";
            } elseif (\substr_compare($signature, '): never', -\strlen('): never')) === 0 || \substr_compare($signature, '): void', -\strlen('): void')) === 0) {
                $body = <<<EOPHP
        if (isset(\$this->lazyObjectReal)) {
            \$this->lazyObjectReal->{$method->name}(...\\func_get_args());
        } else {
            {$parentCall};
        }
EOPHP;
            } else {
                if (!$methodsHaveToBeProxied && !$method->isAbstract()) {
                    // Skip proxying methods that might return $this
                    foreach (\preg_split('/[()|&]++/', self::exportType($method) ?? 'static') as $type) {
                        if (\in_array($type = \ltrim($type, '?'), ['static', 'object'], \true)) {
                            continue 2;
                        }
                        foreach (\array_merge([$class], $interfaces) as $r) {
                            if ($r && \is_a($r->name, $type, \true)) {
                                continue 3;
                            }
                        }
                    }
                }
                $body = <<<EOPHP
        if (isset(\$this->lazyObjectReal)) {
            return \$this->lazyObjectReal->{$method->name}(...\\func_get_args());
        }

        return {$parentCall};
EOPHP;
            }
            $methods[$lcName] = "    {$signature}\n    {\n{$body}\n    }";
        }
        $types = $interfaces = \array_unique(\array_column($interfaces, 'name'));
        $interfaces[] = LazyObjectInterface::class;
        $interfaces = \implode(', \\', $interfaces);
        $parent = $class ? ' extends \\' . $class->name : '';
        \array_unshift($types, $class ? 'parent' : '');
        $type = \ltrim(\implode('&\\', $types), '&');
        if (!$class) {
            $trait = new \ReflectionMethod(LazyProxyTrait::class, 'initializeLazyObject');
            $body = \array_slice(\file($trait->getFileName()), $trait->getStartLine() - 1, $trait->getEndLine() - $trait->getStartLine());
            $body[0] = \str_replace('): parent', '): ' . $type, $body[0]);
            $methods = ['initializeLazyObject' => \implode('', $body) . '    }'] + $methods;
        }
        $body = $methods ? "\n" . \implode("\n\n", $methods) . "\n" : '';
        $propertyScopes = $class ? \substr(self::exportPropertyScopes($class->name), 1, -6) : '';
        return <<<EOPHP
{$parent} implements \\{$interfaces}
{
    use \\Symfony\\Component\\VarExporter\\LazyProxyTrait;

    private const LAZY_OBJECT_PROPERTY_SCOPES = [
        'lazyObjectReal' => [self::class, 'lazyObjectReal', null],
        "\\0".self::class."\\0lazyObjectReal" => [self::class, 'lazyObjectReal', null],{$propertyScopes}
    ];
{$body}}

// Help opcache.preload discover always-needed symbols
class_exists(\\Symfony\\Component\\VarExporter\\Internal\\Hydrator::class);
class_exists(\\Symfony\\Component\\VarExporter\\Internal\\LazyObjectRegistry::class);
class_exists(\\Symfony\\Component\\VarExporter\\Internal\\LazyObjectState::class);

EOPHP;
    }
    public static function exportSignature(\ReflectionFunctionAbstract $function, bool $withParameterTypes = \true) : string
    {
        $parameters = [];
        foreach ($function->getParameters() as $param) {
            $parameters[] = ((\method_exists($param, 'getAttributes') ? $param->getAttributes(\ConfigTransformer202212\SensitiveParameter::class) : []) ? '#[\\SensitiveParameter] ' : '') . ($withParameterTypes && $param->hasType() ? self::exportType($param) . ' ' : '') . ($param->isPassedByReference() ? '&' : '') . ($param->isVariadic() ? '...' : '') . '$' . $param->name . ($param->isOptional() && !$param->isVariadic() ? ' = ' . self::exportDefault($param) : '');
        }
        $signature = 'function ' . ($function->returnsReference() ? '&' : '') . ($function->isClosure() ? '' : $function->name) . '(' . \implode(', ', $parameters) . ')';
        if ($function instanceof \ReflectionMethod) {
            $signature = ($function->isPublic() ? 'public ' : ($function->isProtected() ? 'protected ' : 'private ')) . ($function->isStatic() ? 'static ' : '') . $signature;
        }
        if ($function->hasReturnType()) {
            $signature .= ': ' . self::exportType($function);
        }
        static $getPrototype;
        $getPrototype = $getPrototype ?? \Closure::fromCallable([new \ReflectionMethod(\ReflectionMethod::class, 'getPrototype'), 'invoke']);
        while ($function) {
            if ($function->hasTentativeReturnType()) {
                return '#[\\ReturnTypeWillChange] ' . $signature;
            }
            try {
                $function = $function instanceof \ReflectionMethod && $function->isAbstract() ? \false : $getPrototype($function);
            } catch (\ReflectionException $exception) {
                break;
            }
        }
        return $signature;
    }
    /**
     * @param \ReflectionFunctionAbstract|\ReflectionProperty|\ReflectionParameter $owner
     */
    public static function exportType($owner, bool $noBuiltin = \false, \ReflectionType $type = null) : ?string
    {
        if (!($type = $type ?? ($owner instanceof \ReflectionFunctionAbstract ? $owner->getReturnType() : $owner->getType()))) {
            return null;
        }
        $class = null;
        $types = [];
        if ($type instanceof \ReflectionUnionType) {
            $reflectionTypes = $type->getTypes();
            $glue = '|';
        } elseif ($type instanceof \ReflectionIntersectionType) {
            $reflectionTypes = $type->getTypes();
            $glue = '&';
        } else {
            $reflectionTypes = [$type];
            $glue = null;
        }
        foreach ($reflectionTypes as $type) {
            if ($type instanceof \ReflectionIntersectionType) {
                if ('' !== ($name = '(' . self::exportType($owner, $noBuiltin, $type) . ')')) {
                    $types[] = $name;
                }
                continue;
            }
            $name = $type->getName();
            if ($noBuiltin && $type->isBuiltin()) {
                continue;
            }
            if (\in_array($name, ['parent', 'self'], \true) && ($class = $class ?? $owner->getDeclaringClass())) {
                $name = 'parent' === $name ? (($_ = $class->getParentClass() ?: null) ? $_->name : null) ?? 'parent' : $class->name;
            }
            $types[] = ($noBuiltin || $type->isBuiltin() || 'static' === $name ? '' : '\\') . $name;
        }
        if (!$types) {
            return '';
        }
        if (null === $glue) {
            return (!$noBuiltin && $type->allowsNull() && 'mixed' !== $name ? '?' : '') . $types[0];
        }
        \sort($types);
        return \implode($glue, $types);
    }
    private static function exportPropertyScopes(string $parent) : string
    {
        $propertyScopes = Hydrator::$propertyScopes[$parent] = Hydrator::$propertyScopes[$parent] ?? Hydrator::getPropertyScopes($parent);
        \uksort($propertyScopes, 'strnatcmp');
        $propertyScopes = VarExporter::export($propertyScopes);
        $propertyScopes = \str_replace(VarExporter::export($parent), 'parent::class', $propertyScopes);
        $propertyScopes = \preg_replace("/(?|(,)\n( )       |\n        |,\n    (\\]))/", '$1$2', $propertyScopes);
        $propertyScopes = \str_replace("\n", "\n    ", $propertyScopes);
        return $propertyScopes;
    }
    private static function exportDefault(\ReflectionParameter $param) : string
    {
        $default = \rtrim(\substr(\explode('$' . $param->name . ' = ', (string) $param, 2)[1] ?? '', 0, -2));
        if (\in_array($default, ['<default>', 'NULL'], \true)) {
            return 'null';
        }
        if (\substr_compare($default, "...'", -\strlen("...'")) === 0 && \preg_match("/^'(?:[^'\\\\]*+(?:\\\\.)*+)*+'\$/", $default)) {
            return VarExporter::export($param->getDefaultValue());
        }
        $regexp = "/(\"(?:[^\"\\\\]*+(?:\\\\.)*+)*+\"|'(?:[^'\\\\]*+(?:\\\\.)*+)*+')/";
        $parts = \preg_split($regexp, $default, -1, \PREG_SPLIT_DELIM_CAPTURE | \PREG_SPLIT_NO_EMPTY);
        $regexp = '/([\\[\\( ]|^)([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+(?:\\\\[a-zA-Z0-9_\\x7f-\\xff]++)*+)(?!: )/';
        switch ($m[2]) {
            case 'new':
            case 'false':
            case 'true':
            case 'null':
                $callback = $m[2];
                break;
            case 'NULL':
                $callback = 'null';
                break;
            case 'self':
                $callback = '\\' . $class->name;
                break;
            case 'namespace\\parent':
            case 'parent':
                $callback = ($parent = $class->getParentClass()) ? '\\' . $parent->name : 'parent';
                break;
            default:
                $callback = '\\' . $m[2];
                break;
        }
        return \implode('', \array_map(function ($part) use($regexp, $callback) {
            switch ($part[0]) {
                case '"':
                    return $part;
                case "'":
                    return \false !== \strpbrk($part, "\\\x00\r\n") ? '"' . \substr(\str_replace(['$', "\x00", "\r", "\n"], ['\\$', '\\0', '\\r', '\\n'], $part), 1, -1) . '"' : $part;
                default:
                    return \preg_replace_callback($regexp, $callback, $part);
            }
        }, $parts));
    }
}
