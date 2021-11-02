<?php

declare (strict_types=1);
namespace ConfigTransformer202111026\Symplify\PhpConfigPrinter\ServiceOptionConverter;

use ConfigTransformer202111026\PhpParser\BuilderHelpers;
use ConfigTransformer202111026\PhpParser\Node\Arg;
use ConfigTransformer202111026\PhpParser\Node\Expr\MethodCall;
use ConfigTransformer202111026\Symplify\PhpConfigPrinter\Contract\Converter\ServiceOptionsKeyYamlToPhpFactoryInterface;
use ConfigTransformer202111026\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class ParentLazyServiceOptionKeyYamlToPhpFactory implements \ConfigTransformer202111026\Symplify\PhpConfigPrinter\Contract\Converter\ServiceOptionsKeyYamlToPhpFactoryInterface
{
    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     */
    public function decorateServiceMethodCall($key, $yaml, $values, $methodCall) : \ConfigTransformer202111026\PhpParser\Node\Expr\MethodCall
    {
        $method = $key;
        $methodCall = new \ConfigTransformer202111026\PhpParser\Node\Expr\MethodCall($methodCall, $method);
        $methodCall->args[] = new \ConfigTransformer202111026\PhpParser\Node\Arg(\ConfigTransformer202111026\PhpParser\BuilderHelpers::normalizeValue($values[$key]));
        return $methodCall;
    }
    /**
     * @param mixed $key
     * @param mixed $values
     */
    public function isMatch($key, $values) : bool
    {
        return \in_array($key, [\ConfigTransformer202111026\Symplify\PhpConfigPrinter\ValueObject\YamlKey::PARENT, \ConfigTransformer202111026\Symplify\PhpConfigPrinter\ValueObject\YamlKey::LAZY], \true);
    }
}
