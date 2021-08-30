<?php

declare (strict_types=1);
namespace ConfigTransformer202108307\Symplify\PhpConfigPrinter\ExprResolver;

use ConfigTransformer202108307\PhpParser\Node\Expr;
use ConfigTransformer202108307\Symfony\Component\Yaml\Tag\TaggedValue;
use ConfigTransformer202108307\Symplify\PhpConfigPrinter\ValueObject\FunctionName;
final class TaggedServiceResolver
{
    /**
     * @var \Symplify\PhpConfigPrinter\ExprResolver\ServiceReferenceExprResolver
     */
    private $serviceReferenceExprResolver;
    public function __construct(\ConfigTransformer202108307\Symplify\PhpConfigPrinter\ExprResolver\ServiceReferenceExprResolver $serviceReferenceExprResolver)
    {
        $this->serviceReferenceExprResolver = $serviceReferenceExprResolver;
    }
    public function resolve(\ConfigTransformer202108307\Symfony\Component\Yaml\Tag\TaggedValue $taggedValue) : \ConfigTransformer202108307\PhpParser\Node\Expr
    {
        $serviceName = $taggedValue->getValue()['class'];
        $functionName = \ConfigTransformer202108307\Symplify\PhpConfigPrinter\ValueObject\FunctionName::INLINE_SERVICE;
        return $this->serviceReferenceExprResolver->resolveServiceReferenceExpr($serviceName, \false, $functionName);
    }
}
