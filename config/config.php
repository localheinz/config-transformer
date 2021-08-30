<?php

declare (strict_types=1);
namespace ConfigTransformer202108307;

use ConfigTransformer202108307\PhpParser\BuilderFactory;
use ConfigTransformer202108307\PhpParser\NodeFinder;
use ConfigTransformer202108307\Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use ConfigTransformer202108307\Symfony\Component\Yaml\Parser;
use ConfigTransformer202108307\Symplify\ConfigTransformer\Console\ConfigTransfomerConsoleApplication;
use ConfigTransformer202108307\Symplify\ConfigTransformer\Provider\YamlContentProvider;
use ConfigTransformer202108307\Symplify\PackageBuilder\Console\Command\CommandNaming;
use ConfigTransformer202108307\Symplify\PackageBuilder\Reflection\ClassLikeExistenceChecker;
use ConfigTransformer202108307\Symplify\PackageBuilder\Yaml\ParametersMerger;
use ConfigTransformer202108307\Symplify\PhpConfigPrinter\Contract\YamlFileContentProviderInterface;
use ConfigTransformer202108307\Symplify\SmartFileSystem\FileSystemFilter;
return static function (\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();
    $services->load('ConfigTransformer202108307\Symplify\ConfigTransformer\\', __DIR__ . '/../src')->exclude([__DIR__ . '/../src/HttpKernel', __DIR__ . '/../src/DependencyInjection/Loader', __DIR__ . '/../src/ValueObject']);
    // console
    $services->set(\ConfigTransformer202108307\Symplify\ConfigTransformer\Console\ConfigTransfomerConsoleApplication::class);
    $services->alias(\ConfigTransformer202108307\Symfony\Component\Console\Application::class, \ConfigTransformer202108307\Symplify\ConfigTransformer\Console\ConfigTransfomerConsoleApplication::class);
    $services->set(\ConfigTransformer202108307\Symplify\PackageBuilder\Console\Command\CommandNaming::class);
    $services->set(\ConfigTransformer202108307\PhpParser\BuilderFactory::class);
    $services->set(\ConfigTransformer202108307\PhpParser\NodeFinder::class);
    $services->set(\ConfigTransformer202108307\Symfony\Component\Yaml\Parser::class);
    $services->set(\ConfigTransformer202108307\Symplify\SmartFileSystem\FileSystemFilter::class);
    $services->alias(\ConfigTransformer202108307\Symplify\PhpConfigPrinter\Contract\YamlFileContentProviderInterface::class, \ConfigTransformer202108307\Symplify\ConfigTransformer\Provider\YamlContentProvider::class);
    $services->set(\ConfigTransformer202108307\Symplify\PackageBuilder\Reflection\ClassLikeExistenceChecker::class);
    $services->set(\ConfigTransformer202108307\Symplify\PackageBuilder\Yaml\ParametersMerger::class);
};
