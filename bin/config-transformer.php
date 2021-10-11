<?php

declare (strict_types=1);
namespace ConfigTransformer2021101110;

use ConfigTransformer2021101110\Symplify\ConfigTransformer\HttpKernel\ConfigTransformerKernel;
use ConfigTransformer2021101110\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun;
$possibleAutoloadPaths = [
    // monorepo
    __DIR__ . '/../../../vendor/autoload.php',
    // after split package
    __DIR__ . '/../vendor/autoload.php',
    // dependency
    __DIR__ . '/../../../autoload.php',
];
foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
    if (\file_exists($possibleAutoloadPath)) {
        require_once $possibleAutoloadPath;
        break;
    }
}
$scoperAutoloadFilepath = __DIR__ . '/../vendor/scoper-autoload.php';
if (\file_exists($scoperAutoloadFilepath)) {
    require_once $scoperAutoloadFilepath;
}
$codeSnifferAutoload = \getcwd() . '/vendor/squizlabs/php_codesniffer/autoload.php';
if (\file_exists($codeSnifferAutoload)) {
    require_once $codeSnifferAutoload;
}
$kernelBootAndApplicationRun = new \ConfigTransformer2021101110\Symplify\SymplifyKernel\ValueObject\KernelBootAndApplicationRun(\ConfigTransformer2021101110\Symplify\ConfigTransformer\HttpKernel\ConfigTransformerKernel::class);
$kernelBootAndApplicationRun->run();
