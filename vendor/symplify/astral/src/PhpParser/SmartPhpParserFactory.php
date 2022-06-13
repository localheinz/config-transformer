<?php

declare (strict_types=1);
namespace ConfigTransformer202206\Symplify\Astral\PhpParser;

use ConfigTransformer202206\PhpParser\Lexer\Emulative;
use ConfigTransformer202206\PhpParser\NodeVisitor\NameResolver;
use ConfigTransformer202206\PhpParser\Parser;
use ConfigTransformer202206\PhpParser\ParserFactory;
use ConfigTransformer202206\PHPStan\Parser\CachedParser;
use ConfigTransformer202206\PHPStan\Parser\SimpleParser;
/**
 * Based on PHPStan-based PHP-Parser best practices:
 *
 * @see https://github.com/rectorphp/rector/issues/6744#issuecomment-950282826
 * @see https://github.com/phpstan/phpstan-src/blob/99e4ae0dced58fe0be7a7aec3168a5e9d639240a/conf/config.neon#L1669-L1691
 */
final class SmartPhpParserFactory
{
    public function create() : SmartPhpParser
    {
        $nativePhpParser = $this->createNativePhpParser();
        $cachedParser = $this->createPHPStanParser($nativePhpParser);
        return new SmartPhpParser($cachedParser);
    }
    private function createNativePhpParser() : Parser
    {
        $parserFactory = new ParserFactory();
        $lexerEmulative = new Emulative();
        return $parserFactory->create(ParserFactory::PREFER_PHP7, $lexerEmulative);
    }
    private function createPHPStanParser(Parser $parser) : CachedParser
    {
        $nameResolver = new NameResolver();
        $simpleParser = new SimpleParser($parser, $nameResolver);
        return new CachedParser($simpleParser, 1024);
    }
}
