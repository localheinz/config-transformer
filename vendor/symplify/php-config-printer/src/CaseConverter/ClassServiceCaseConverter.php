<?php

declare (strict_types=1);
namespace ConfigTransformer2022012510\Symplify\PhpConfigPrinter\CaseConverter;

use ConfigTransformer2022012510\PhpParser\Node\Expr\MethodCall;
use ConfigTransformer2022012510\PhpParser\Node\Expr\Variable;
use ConfigTransformer2022012510\PhpParser\Node\Stmt\Expression;
use ConfigTransformer2022012510\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use ConfigTransformer2022012510\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory;
use ConfigTransformer2022012510\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory;
use ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\MethodName;
use ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class ClassServiceCaseConverter implements \ConfigTransformer2022012510\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @var \Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory
     */
    private $argsNodeFactory;
    /**
     * @var \Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory
     */
    private $serviceOptionNodeFactory;
    public function __construct(\ConfigTransformer2022012510\Symplify\PhpConfigPrinter\NodeFactory\ArgsNodeFactory $argsNodeFactory, \ConfigTransformer2022012510\Symplify\PhpConfigPrinter\NodeFactory\Service\ServiceOptionNodeFactory $serviceOptionNodeFactory)
    {
        $this->argsNodeFactory = $argsNodeFactory;
        $this->serviceOptionNodeFactory = $serviceOptionNodeFactory;
    }
    /**
     * @param mixed $key
     * @param mixed $values
     */
    public function convertToMethodCall($key, $values) : \ConfigTransformer2022012510\PhpParser\Node\Stmt\Expression
    {
        $args = $this->argsNodeFactory->createFromValues([$key, $values[\ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY]]);
        $methodCall = new \ConfigTransformer2022012510\PhpParser\Node\Expr\MethodCall(new \ConfigTransformer2022012510\PhpParser\Node\Expr\Variable(\ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES), \ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\MethodName::SET, $args);
        unset($values[\ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY]);
        $decoratedMethodCall = $this->serviceOptionNodeFactory->convertServiceOptionsToNodes($values, $methodCall);
        return new \ConfigTransformer2022012510\PhpParser\Node\Stmt\Expression($decoratedMethodCall);
    }
    /**
     * @param mixed $key
     * @param mixed $values
     */
    public function match(string $rootKey, $key, $values) : bool
    {
        if ($rootKey !== \ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\YamlKey::SERVICES) {
            return \false;
        }
        if (\is_array($values) && \count($values) !== 1) {
            return \false;
        }
        if (!isset($values[\ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\YamlKey::CLASS_KEY])) {
            return \false;
        }
        return !isset($values[\ConfigTransformer2022012510\Symplify\PhpConfigPrinter\ValueObject\YamlKey::ALIAS]);
    }
}
