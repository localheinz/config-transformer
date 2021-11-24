<?php

declare (strict_types=1);
namespace ConfigTransformer202111248\Symplify\SmartFileSystem\Finder;

use ConfigTransformer202111248\Symfony\Component\Finder\Finder;
use ConfigTransformer202111248\Symplify\SmartFileSystem\FileSystemFilter;
use ConfigTransformer202111248\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @api
 * @see \Symplify\SmartFileSystem\Tests\Finder\SmartFinder\SmartFinderTest
 */
final class SmartFinder
{
    /**
     * @var \Symplify\SmartFileSystem\Finder\FinderSanitizer
     */
    private $finderSanitizer;
    /**
     * @var \Symplify\SmartFileSystem\FileSystemFilter
     */
    private $fileSystemFilter;
    public function __construct(\ConfigTransformer202111248\Symplify\SmartFileSystem\Finder\FinderSanitizer $finderSanitizer, \ConfigTransformer202111248\Symplify\SmartFileSystem\FileSystemFilter $fileSystemFilter)
    {
        $this->finderSanitizer = $finderSanitizer;
        $this->fileSystemFilter = $fileSystemFilter;
    }
    /**
     * @param string[] $directoriesOrFiles
     * @return SmartFileInfo[]
     */
    public function findPaths(array $directoriesOrFiles, string $path) : array
    {
        $directories = $this->fileSystemFilter->filterDirectories($directoriesOrFiles);
        $fileInfos = [];
        if ($directories !== []) {
            $finder = new \ConfigTransformer202111248\Symfony\Component\Finder\Finder();
            $finder->name('*')->in($directories)->path($path)->files()->sortByName();
            $fileInfos = $this->finderSanitizer->sanitize($finder);
        }
        return $fileInfos;
    }
    /**
     * @param string[] $directoriesOrFiles
     * @param string[] $excludedDirectories
     * @return SmartFileInfo[]
     */
    public function find(array $directoriesOrFiles, string $name, array $excludedDirectories = []) : array
    {
        $directories = $this->fileSystemFilter->filterDirectories($directoriesOrFiles);
        $fileInfos = [];
        if ($directories !== []) {
            $finder = new \ConfigTransformer202111248\Symfony\Component\Finder\Finder();
            $finder->name($name)->in($directories)->files()->sortByName();
            if ($excludedDirectories !== []) {
                $finder->exclude($excludedDirectories);
            }
            $fileInfos = $this->finderSanitizer->sanitize($finder);
        }
        $files = $this->fileSystemFilter->filterFiles($directoriesOrFiles);
        foreach ($files as $file) {
            $fileInfos[] = new \ConfigTransformer202111248\Symplify\SmartFileSystem\SmartFileInfo($file);
        }
        return $fileInfos;
    }
}
