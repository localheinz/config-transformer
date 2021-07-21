<?php

declare (strict_types=1);
namespace ConfigTransformer2021072110\Symplify\PhpConfigPrinter\CaseConverter;

use ConfigTransformer2021072110\PhpParser\Node\Arg;
use ConfigTransformer2021072110\PhpParser\Node\Expr\MethodCall;
use ConfigTransformer2021072110\PhpParser\Node\Expr\Variable;
use ConfigTransformer2021072110\PhpParser\Node\Stmt\Expression;
use ConfigTransformer2021072110\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface;
use ConfigTransformer2021072110\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory;
use ConfigTransformer2021072110\Symplify\PhpConfigPrinter\ValueObject\VariableName;
use ConfigTransformer2021072110\Symplify\PhpConfigPrinter\ValueObject\YamlKey;
final class NameOnlyServiceCaseConverter implements \ConfigTransformer2021072110\Symplify\PhpConfigPrinter\Contract\CaseConverterInterface
{
    /**
     * @var \Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory
     */
    private $commonNodeFactory;
    public function __construct(\ConfigTransformer2021072110\Symplify\PhpConfigPrinter\NodeFactory\CommonNodeFactory $commonNodeFactory)
    {
        $this->commonNodeFactory = $commonNodeFactory;
    }
    public function convertToMethodCall($key, $values) : \ConfigTransformer2021072110\PhpParser\Node\Stmt\Expression
    {
        $classConstFetch = $this->commonNodeFactory->createClassReference($key);
        $setMethodCall = new \ConfigTransformer2021072110\PhpParser\Node\Expr\MethodCall(new \ConfigTransformer2021072110\PhpParser\Node\Expr\Variable(\ConfigTransformer2021072110\Symplify\PhpConfigPrinter\ValueObject\VariableName::SERVICES), 'set', [new \ConfigTransformer2021072110\PhpParser\Node\Arg($classConstFetch)]);
        return new \ConfigTransformer2021072110\PhpParser\Node\Stmt\Expression($setMethodCall);
    }
    /**
     * @param string $rootKey
     */
    public function match($rootKey, $key, $values) : bool
    {
        if ($rootKey !== \ConfigTransformer2021072110\Symplify\PhpConfigPrinter\ValueObject\YamlKey::SERVICES) {
            return \false;
        }
        return $values === null || $values === [];
    }
}
