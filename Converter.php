<?php

namespace Opstalent\PdfToHtml;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Patryk Grudniewski <patgrudniewski@gmail.com>
 * @package Opstalent\PdfToHtml
 */
class Converter
{
    /**
     * @var string
     */
    protected static $outputDir = '/tmp/opstalent/pdftohtml/';

    /**
     * @var string
     */
    protected static $flags = '-s -noframes';

    /**
     * @var string
     */
    protected static $outputFilenameBase = 'output';

    /**
     * @param \SplFileInfo $pdfFile
     * @return Crawler
     */
    public static function convert(\SplFileInfo $pdfFile) : Crawler
    {
        $fs = new Filesystem();

        if ($pdfFile->getMimeType() != 'application/pdf') {
            throw new \InvalidArgumentException(sprintf('MIME type of converted file is expected to be "application/pdf", "%s" given', $pdfFile->getMimeType()));
        }

        $htmlFileBase = 'output';

        $fs->remove(static::$outputDir);
        $fs->mkdir(static::$outputDir);

        $command = sprintf(
            'pdftohtml %s %s %s',
            static::$flags,
            $pdfFile->getRealPath(),
            static::$outputDir . static::$outputFilenameBase
        );

        $output = [];
        $return = 0;
        exec($command, $output, $return);

        $htmlFile = static::$outputDir . static::$outputFilenameBase . '.html';
        $html = file_get_contents($htmlFile);

        $fs->remove(static::$outputDir);

        return new Crawler($html);
    }
}
