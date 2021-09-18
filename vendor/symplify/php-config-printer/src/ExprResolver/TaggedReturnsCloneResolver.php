<?php

declare (strict_types=1);
namespace ConfigTransformer2021091810\Symplify\PhpConfigPrinter\ExprResolver;

use ConfigTransformer2021091810\PhpParser\Node\Expr\Array_;
use ConfigTransformer2021091810\PhpParser\Node\Expr\ArrayItem;
use ConfigTransformer2021091810\Symfony\Component\Yaml\Tag\TaggedValue;
use ConfigTransformer2021091810\Symplify\PhpConfigPrinter\ValueObject\FunctionName;
final class TaggedReturnsCloneResolver
{
    /**
     * @var \Symplify\PhpConfigPrinter\ExprResolver\ServiceReferenceExprResolver
     */
    private $serviceReferenceExprResolver;
    public function __construct(\ConfigTransformer2021091810\Symplify\PhpConfigPrinter\ExprResolver\ServiceReferenceExprResolver $serviceReferenceExprResolver)
    {
        $this->serviceReferenceExprResolver = $serviceReferenceExprResolver;
    }
    public function resolve(\ConfigTransformer2021091810\Symfony\Component\Yaml\Tag\TaggedValue $taggedValue) : \ConfigTransformer2021091810\PhpParser\Node\Expr\Array_
    {
        $serviceName = $taggedValue->getValue()[0];
        $funcCall = $this->serviceReferenceExprResolver->resolveServiceReferenceExpr($serviceName, \false, \ConfigTransformer2021091810\Symplify\PhpConfigPrinter\ValueObject\FunctionName::SERVICE);
        return new \ConfigTransformer2021091810\PhpParser\Node\Expr\Array_([new \ConfigTransformer2021091810\PhpParser\Node\Expr\ArrayItem($funcCall)]);
    }
}
