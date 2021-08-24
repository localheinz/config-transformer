<?php

declare (strict_types=1);
namespace ConfigTransformer202108241\Symplify\PhpConfigPrinter\CaseConverter;

use ConfigTransformer202108241\PhpParser\Node\Expr\MethodCall;
use ConfigTransformer202108241\PhpParser\Node\Expr\Variable;
use ConfigTransformer202108241\PhpParser\Node\Stmt\Expression;
use ConfigTransformer202108241\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use ConfigTransformer202108241\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory;
use ConfigTransformer202108241\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory;
use ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\MethodName;
use ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class ConfiguredServiceCaseConverter implements \ConfigTransformer202108241\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @var \Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory
     */
    private $argsNodeFactory;
    /**
     * @var \Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory
     */
    private $serviceOptionNodeFactory;
    public function __construct(\ConfigTransformer202108241\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory $argsNodeFactory, \ConfigTransformer202108241\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory $serviceOptionNodeFactory)
    {
        $this->argsNodeFactory = $argsNodeFactory;
        $this->serviceOptionNodeFactory = $serviceOptionNodeFactory;
    }
    public function convertToMethodCall($key, $values) : \ConfigTransformer202108241\PhpParser\Node\Stmt\Expression
    {
        $valuesForArgs = [$key];
        if (isset($values[\ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY])) {
            $valuesForArgs[] = $values[\ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY];
        }
        $args = $this->argsNodeFactory->createFromValues($valuesForArgs);
        $methodCall = new \ConfigTransformer202108241\PhpParser\Node\Expr\MethodCall(new \ConfigTransformer202108241\PhpParser\Node\Expr\Variable(\ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES), \ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\MethodName::SET, $args);
        $decoratedMethodCall = $this->serviceOptionNodeFactory->convertServiceOptionsToNodes($values, $methodCall);
        return new \ConfigTransformer202108241\PhpParser\Node\Stmt\Expression($decoratedMethodCall);
    }
    /**
     * @param string $rootKey
     */
    public function match($rootKey, $key, $values) : bool
    {
        if ($rootKey !== \ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::SERVICES) {
            return \false;
        }
        if ($key === \ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::_DEFAULTS) {
            return \false;
        }
        if ($key === \ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::_INSTANCEOF) {
            return \false;
        }
        if (isset($values[\ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::RESOURCE])) {
            return \false;
        }
        // handled by @see \Symplify\PhpConfigPrinter\CaseConverter\CaseConverter\AliasCaseConverter
        if ($this->isAlias($values)) {
            return \false;
        }
        if ($values === null) {
            return \false;
        }
        return $values !== [];
    }
    private function isAlias($values) : bool
    {
        if (isset($values[\ConfigTransformer202108241\Symplify\PhpConfigPrinter\ValueObject\YamlKey::ALIAS])) {
            return \true;
        }
        if (!\is_string($values)) {
            return \false;
        }
        return \strncmp($values, '@', \strlen('@')) === 0;
    }
}
