<?php

declare (strict_types=1);
namespace ConfigTransformer2021061810\Symplify\AutowireArrayParameter\DependencyInjection\CompilerPass;

use ConfigTransformer2021061810\Nette\Utils\Strings;
use ReflectionClass;
use ReflectionMethod;
use ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use ConfigTransformer2021061810\Symfony\Component\DependencyInjection\ContainerBuilder;
use ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Definition;
use ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Reference;
use ConfigTransformer2021061810\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver;
use ConfigTransformer2021061810\Symplify\AutowireArrayParameter\Skipper\ParameterSkipper;
use ConfigTransformer2021061810\Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver;
use ConfigTransformer2021061810\Symplify\PackageBuilder\DependencyInjection\DefinitionFinder;
/**
 * @inspiration https://github.com/nette/di/pull/178
 * @see \Symplify\AutowireArrayParameter\Tests\DependencyInjection\CompilerPass\AutowireArrayParameterCompilerPassTest
 */
final class AutowireArrayParameterCompilerPass implements \ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    /**
     * These namespaces are already configured by their bundles/extensions.
     *
     * @var string[]
     */
    private const EXCLUDED_NAMESPACES = ['Doctrine', 'JMS', 'Symfony', 'Sensio', 'Knp', 'EasyCorp', 'Sonata', 'Twig'];
    /**
     * Classes that create circular dependencies
     *
     * @var string[]
     * @noRector
     */
    private $excludedFatalClasses = ['ConfigTransformer2021061810\\Symfony\\Component\\Form\\FormExtensionInterface', 'ConfigTransformer2021061810\\Symfony\\Component\\Asset\\PackageInterface', 'ConfigTransformer2021061810\\Symfony\\Component\\Config\\Loader\\LoaderInterface', 'ConfigTransformer2021061810\\Symfony\\Component\\VarDumper\\Dumper\\ContextProvider\\ContextProviderInterface', 'ConfigTransformer2021061810\\EasyCorp\\Bundle\\EasyAdminBundle\\Form\\Type\\Configurator\\TypeConfiguratorInterface', 'ConfigTransformer2021061810\\Sonata\\CoreBundle\\Model\\Adapter\\AdapterInterface', 'ConfigTransformer2021061810\\Sonata\\Doctrine\\Adapter\\AdapterChain', 'ConfigTransformer2021061810\\Sonata\\Twig\\Extension\\TemplateExtension', 'ConfigTransformer2021061810\\Symfony\\Component\\HttpKernel\\KernelInterface'];
    /**
     * @var \Symplify\PackageBuilder\DependencyInjection\DefinitionFinder
     */
    private $definitionFinder;
    /**
     * @var \Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver
     */
    private $parameterTypeResolver;
    /**
     * @var \Symplify\AutowireArrayParameter\Skipper\ParameterSkipper
     */
    private $parameterSkipper;
    /**
     * @param string[] $excludedFatalClasses
     */
    public function __construct(array $excludedFatalClasses = [])
    {
        $this->definitionFinder = new \ConfigTransformer2021061810\Symplify\PackageBuilder\DependencyInjection\DefinitionFinder();
        $paramTypeDocBlockResolver = new \ConfigTransformer2021061810\Symplify\AutowireArrayParameter\DocBlock\ParamTypeDocBlockResolver();
        $this->parameterTypeResolver = new \ConfigTransformer2021061810\Symplify\AutowireArrayParameter\TypeResolver\ParameterTypeResolver($paramTypeDocBlockResolver);
        $this->parameterSkipper = new \ConfigTransformer2021061810\Symplify\AutowireArrayParameter\Skipper\ParameterSkipper($this->parameterTypeResolver, $excludedFatalClasses);
    }
    public function process(\ConfigTransformer2021061810\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            if ($this->shouldSkipDefinition($containerBuilder, $definition)) {
                continue;
            }
            /** @var ReflectionClass<object> $reflectionClass */
            $reflectionClass = $containerBuilder->getReflectionClass($definition->getClass());
            /** @var ReflectionMethod $constructorReflectionMethod */
            $constructorReflectionMethod = $reflectionClass->getConstructor();
            $this->processParameters($containerBuilder, $constructorReflectionMethod, $definition);
        }
    }
    private function shouldSkipDefinition(\ConfigTransformer2021061810\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, \ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Definition $definition) : bool
    {
        if ($definition->isAbstract()) {
            return \true;
        }
        if ($definition->getClass() === null) {
            return \true;
        }
        // here class name can be "%parameter.class%"
        $parameterBag = $containerBuilder->getParameterBag();
        $resolvedClassName = $parameterBag->resolveValue($definition->getClass());
        // skip 3rd party classes, they're autowired by own config
        $excludedNamespacePattern = '#^(' . \implode('|', self::EXCLUDED_NAMESPACES) . ')\\\\#';
        if (\ConfigTransformer2021061810\Nette\Utils\Strings::match($resolvedClassName, $excludedNamespacePattern)) {
            return \true;
        }
        if (\in_array($resolvedClassName, $this->excludedFatalClasses, \true)) {
            return \true;
        }
        if ($definition->getFactory()) {
            return \true;
        }
        if (!\class_exists($definition->getClass())) {
            return \true;
        }
        $reflectionClass = $containerBuilder->getReflectionClass($definition->getClass());
        if (!$reflectionClass instanceof \ReflectionClass) {
            return \true;
        }
        if (!$reflectionClass->hasMethod('__construct')) {
            return \true;
        }
        /** @var ReflectionMethod $constructorReflectionMethod */
        $constructorReflectionMethod = $reflectionClass->getConstructor();
        return !$constructorReflectionMethod->getParameters();
    }
    private function processParameters(\ConfigTransformer2021061810\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder, \ReflectionMethod $reflectionMethod, \ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Definition $definition) : void
    {
        $reflectionParameters = $reflectionMethod->getParameters();
        foreach ($reflectionParameters as $reflectionParameter) {
            if ($this->parameterSkipper->shouldSkipParameter($reflectionMethod, $definition, $reflectionParameter)) {
                continue;
            }
            $parameterType = $this->parameterTypeResolver->resolveParameterType($reflectionParameter->getName(), $reflectionMethod);
            if ($parameterType === null) {
                continue;
            }
            $definitionsOfType = $this->definitionFinder->findAllByType($containerBuilder, $parameterType);
            $definitionsOfType = $this->filterOutAbstractDefinitions($definitionsOfType);
            $argumentName = '$' . $reflectionParameter->getName();
            $definition->setArgument($argumentName, $this->createReferencesFromDefinitions($definitionsOfType));
        }
    }
    /**
     * Abstract definitions cannot be the target of references
     *
     * @param Definition[] $definitions
     * @return Definition[]
     */
    private function filterOutAbstractDefinitions(array $definitions) : array
    {
        foreach ($definitions as $key => $definition) {
            if ($definition->isAbstract()) {
                unset($definitions[$key]);
            }
        }
        return $definitions;
    }
    /**
     * @param Definition[] $definitions
     * @return Reference[]
     */
    private function createReferencesFromDefinitions(array $definitions) : array
    {
        $references = [];
        $definitionOfTypeNames = \array_keys($definitions);
        foreach ($definitionOfTypeNames as $definitionOfTypeName) {
            $references[] = new \ConfigTransformer2021061810\Symfony\Component\DependencyInjection\Reference($definitionOfTypeName);
        }
        return $references;
    }
}
