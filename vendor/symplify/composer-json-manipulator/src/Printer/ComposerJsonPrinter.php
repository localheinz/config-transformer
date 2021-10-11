<?php

declare (strict_types=1);
namespace ConfigTransformer202110119\Symplify\ComposerJsonManipulator\Printer;

use ConfigTransformer202110119\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use ConfigTransformer202110119\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson;
use ConfigTransformer202110119\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @api
 */
final class ComposerJsonPrinter
{
    /**
     * @var \Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager
     */
    private $jsonFileManager;
    public function __construct(\ConfigTransformer202110119\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager $jsonFileManager)
    {
        $this->jsonFileManager = $jsonFileManager;
    }
    /**
     * @param string|\Symplify\SmartFileSystem\SmartFileInfo $targetFile
     */
    public function print(\ConfigTransformer202110119\Symplify\ComposerJsonManipulator\ValueObject\ComposerJson $composerJson, $targetFile) : string
    {
        if (\is_string($targetFile)) {
            return $this->jsonFileManager->printComposerJsonToFilePath($composerJson, $targetFile);
        }
        return $this->jsonFileManager->printJsonToFileInfo($composerJson->getJsonArray(), $targetFile);
    }
}
