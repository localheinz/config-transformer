<?php

declare (strict_types=1);
namespace ConfigTransformer202109063\Symplify\EasyTesting\PHPUnit\Behavior;

use ConfigTransformer202109063\Symfony\Component\Finder\Finder;
use ConfigTransformer202109063\Symplify\EasyTesting\ValueObject\ExpectedAndOutputFileInfoPair;
use ConfigTransformer202109063\Symplify\SmartFileSystem\Finder\FinderSanitizer;
use ConfigTransformer202109063\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * Use only in "\PHPUnit\Framework\TestCase"
 *
 * Answer here
 *
 * @see https://stackoverflow.com/questions/54263109/how-to-assert-2-directories-are-identical-in-phpunit
 */
trait DirectoryAssertableTrait
{
    /**
     * @param string $expectedDirectory
     * @param string $outputDirectory
     */
    protected function assertDirectoryEquals($expectedDirectory, $outputDirectory) : void
    {
        $expectedFileInfos = $this->findFileInfosInDirectory($expectedDirectory);
        $outputFileInfos = $this->findFileInfosInDirectory($outputDirectory);
        $fileInfosByRelativeFilePath = $this->groupFileInfosByRelativeFilePath($expectedFileInfos, $expectedDirectory, $outputFileInfos, $outputDirectory);
        foreach ($fileInfosByRelativeFilePath as $relativeFilePath => $expectedAndOutputFileInfoPair) {
            // output file exists
            $this->assertFileExists($outputDirectory . '/' . $relativeFilePath);
            if (!$expectedAndOutputFileInfoPair->doesOutputFileExist()) {
                continue;
            }
            // they have the same content
            $this->assertSame($expectedAndOutputFileInfoPair->getExpectedFileContent(), $expectedAndOutputFileInfoPair->getOutputFileContent(), $relativeFilePath);
        }
    }
    /**
     * @return SmartFileInfo[]
     */
    private function findFileInfosInDirectory(string $directory) : array
    {
        $firstDirectoryFinder = new \ConfigTransformer202109063\Symfony\Component\Finder\Finder();
        $firstDirectoryFinder->files()->in($directory);
        $finderSanitizer = new \ConfigTransformer202109063\Symplify\SmartFileSystem\Finder\FinderSanitizer();
        return $finderSanitizer->sanitize($firstDirectoryFinder);
    }
    /**
     * @param SmartFileInfo[] $expectedFileInfos
     * @param SmartFileInfo[] $outputFileInfos
     * @return array<string, ExpectedAndOutputFileInfoPair>
     */
    private function groupFileInfosByRelativeFilePath(array $expectedFileInfos, string $expectedDirectory, array $outputFileInfos, string $outputDirectory) : array
    {
        $fileInfosByRelativeFilePath = [];
        foreach ($expectedFileInfos as $expectedFileInfo) {
            $relativeFilePath = $expectedFileInfo->getRelativeFilePathFromDirectory($expectedDirectory);
            // match output file info
            $outputFileInfo = $this->resolveFileInfoByRelativeFilePath($outputFileInfos, $outputDirectory, $relativeFilePath);
            $fileInfosByRelativeFilePath[$relativeFilePath] = new \ConfigTransformer202109063\Symplify\EasyTesting\ValueObject\ExpectedAndOutputFileInfoPair($expectedFileInfo, $outputFileInfo);
        }
        return $fileInfosByRelativeFilePath;
    }
    /**
     * @param SmartFileInfo[] $fileInfos
     */
    private function resolveFileInfoByRelativeFilePath(array $fileInfos, string $directory, string $desiredRelativeFilePath) : ?\ConfigTransformer202109063\Symplify\SmartFileSystem\SmartFileInfo
    {
        foreach ($fileInfos as $fileInfo) {
            $relativeFilePath = $fileInfo->getRelativeFilePathFromDirectory($directory);
            if ($desiredRelativeFilePath !== $relativeFilePath) {
                continue;
            }
            return $fileInfo;
        }
        return null;
    }
}
