<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.2.0
 *  @filesource
 *
 */

namespace NodCMS\Core;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * This is a copy of the Codeigniter\ComposerScripts class to copy code
 * that is used by Composer during installs and updates
 * to move files to locations within the system folder so that end-users
 * do not need to use Composer to install a package, but can simply
 * download.
 */
final class ComposerScripts
{
    /**
     * Direct dependencies of CodeIgniter to copy
     * contents to `/system/`.
     *
     * @var array<string, array<string, string>>
     */
    private static $dependencies = [
        'codeigniter' => [
            'from' => __DIR__ . '/../vendor/codeigniter4/framework/system/',
            'to'   => __DIR__ . '/../system/',
        ],
    ];

    /**
     * This static method is called by Composer after every update event,
     * i.e., `composer install`, `composer update`, `composer remove`.
     */
    public static function postUpdate()
    {
        echo __DIR__;
        foreach (self::$dependencies as $dependency) {
            self::recursiveDelete($dependency['to']);
            rmdir($dependency['to']);
            self::recursiveMirror($dependency['from'], $dependency['to']);
        }
    }

    /**
     * Recursively remove the contents of the previous `system/ThirdParty`.
     */
    private static function recursiveDelete(string $directory): void
    {
        if (! is_dir($directory)) {
            echo sprintf('Cannot recursively delete "%s" as it does not exist.', $directory);
        }

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(
                     new RecursiveDirectoryIterator(rtrim($directory, '\\/'), FilesystemIterator::SKIP_DOTS),
                     RecursiveIteratorIterator::CHILD_FIRST
                 ) as $file) {
            $path = $file->getPathname();

            if ($file->isDir()) {
                @rmdir($path);
            } else {
                @unlink($path);
            }
        }
    }

    /**
     * Recursively copy the files and directories of the origin directory
     * into the target directory, i.e. "mirror" its contents.
     */
    private static function recursiveMirror(string $originDir, string $targetDir): void
    {
        $originDir = rtrim($originDir, '\\/');
        $targetDir = rtrim($targetDir, '\\/');

        if (! is_dir($originDir)) {
            echo sprintf('The origin directory "%s" was not found.', $originDir);

            exit(1);
        }

        if (is_dir($targetDir)) {
            echo sprintf('The target directory "%s" is existing. Run %s::recursiveDelete(\'%s\') first.', $targetDir, self::class, $targetDir);

            exit(1);
        }

        @mkdir($targetDir, 0755, true);

        $dirLen = strlen($originDir);

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(
                     new RecursiveDirectoryIterator($originDir, FilesystemIterator::SKIP_DOTS),
                     RecursiveIteratorIterator::SELF_FIRST
                 ) as $file) {
            $origin = $file->getPathname();
            $target = $targetDir . substr($origin, $dirLen);

            if ($file->isDir()) {
                @mkdir($target, 0755);
            } else {
                @copy($origin, $target);
            }
        }
    }
}