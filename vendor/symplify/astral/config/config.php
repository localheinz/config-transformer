<?php

declare (strict_types=1);
namespace ConfigTransformer202111011;

use ConfigTransformer202111011\PhpParser\ConstExprEvaluator;
use ConfigTransformer202111011\PhpParser\NodeFinder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use ConfigTransformer202111011\Symplify\Astral\PhpParser\SmartPhpParser;
use ConfigTransformer202111011\Symplify\Astral\PhpParser\SmartPhpParserFactory;
use ConfigTransformer202111011\Symplify\PackageBuilder\Php\TypeChecker;
use function ConfigTransformer202111011\Symfony\Component\DependencyInjection\Loader\Configurator\service;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->autowire()->autoconfigure()->public();
    $services->load('ConfigTransformer202111011\Symplify\Astral\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/StaticFactory', __DIR__ . '/../src/ValueObject', __DIR__ . '/../src/NodeVisitor', __DIR__ . '/../src/PhpParser/SmartPhpParser.php']);
    $services->set(\ConfigTransformer202111011\Symplify\Astral\PhpParser\SmartPhpParser::class)->factory([\ConfigTransformer202111011\Symfony\Component\DependencyInjection\Loader\Configurator\service(\ConfigTransformer202111011\Symplify\Astral\PhpParser\SmartPhpParserFactory::class), 'create']);
    $services->set(\ConfigTransformer202111011\PhpParser\ConstExprEvaluator::class);
    $services->set(\ConfigTransformer202111011\Symplify\PackageBuilder\Php\TypeChecker::class);
    $services->set(\ConfigTransformer202111011\PhpParser\NodeFinder::class);
};
