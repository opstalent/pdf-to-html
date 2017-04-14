<?php

namespace Opstalent\PdfToHtml\Composer;

/**
 * @author Patryk Grudniewski <patgrudniewski@gmail.com>
 * @package Opstalent\PdfToHtml
 */
final class DependencyResolver
{
    /**
     * @var array
     */
    public static $requiredCommands = [
        'pdftohtml',
    ];

    /**
     * @throws
     */
    public static function resolve()
    {
        $missing = [];
        foreach (static::$requiredCommands as $cmd) {
            if (static::cmdExists($cmd)) {
                continue;
            }

            $missing[] = $cmd;
        }

        if (!empty($missing)) {
            throw new \RuntimeException(sprintf('Followed shell commands are required: %s', implode(', ', $missing)));
        }
    }

    /**
     * @param string
     * @return bool
     */
    private static function cmdExists(string $cmd) : bool
    {
        $which = shell_exec(sprintf('which %s', $cmd));
        return !empty($which);
    }
}
