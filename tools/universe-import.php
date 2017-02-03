<?php


use Bat\FileSystemTool;

require "bigbang.php";

//--------------------------------------------
// DOCUMENTATION - UNIVERSE IMPORT
//--------------------------------------------
/**
 * 2016-12-15
 *
 *
 * Hi,
 * I use this script when using the universe framework inside other kif projects.
 * (https://github.com/lingtalfi/kif)
 *
 *
 *
 *
 * What does this script?
 * --------------------------
 *
 * The script works with two planets directory:
 *
 * - the source is the original universe's planets directory, which is where the universe framework resides on my machine.
 * - the destination is the planets directory in my app (named class-planets because I use kif)
 *
 * Also, there is an _import.txt file in my destination directory, which contains a simple flat txt list of
 * planets names, one per line.
 * This is the list of the planets I need for my project.
 *
 *
 * The script will basically import the planets on my list from the source directory to the destination directory.
 * But it has two working modes:
 *
 * - dir: it copy/pastes the directories
 * - symlink: it creates symlinks instead
 *
 *
 * Note: the script doesn't empty the dest directory before starting.
 * This might change in the future (as this is the most naturally expected behaviour), but
 * in the meantime, it allows me to work in hybrid mode, where I can mix symlinks with new experimental
 * planets that I develop from my project (before turning them into universe's planets).
 *
 *
 * I like to use the "dir" mode when it's time to export the application to the prod server.
 * But when I'm in the dev environment, I prefer the symlink mode, because then I can extend the universe files
 * directly from my project's dir (i.e. I don't need to open the universe project).
 *
 *
 *
 * How to use?
 * -----------------
 *
 * - First configure the script to your needs
 *      - see the "CONFIG" section below
 *      - $planetsDir is the source directory (the planets from the universe framework)
 *      - $destDir is the destination directory (the planets inside your project)
 *
 * - then to execute the script, use one of the following bash commands:
 *
 *     - php -f "/path/to/universe-import.php"
 *          - this command works in symlink mode (default)
 *
 *      - php -f "/path/to/universe-import.php" -- d
 *          - this command works in dir mode (the d option)
 *
 *
 * - Then I would recommend creating tasks (https://github.com/lingtalfi/task-manager) or aliases,
 *    as they speed up your productivity.
 *
 *
 */


//--------------------------------------------
// CONFIG
//--------------------------------------------
$planetsDirWithGit = "/pathto/php/projects/universe/planets"; // working planets dir (with .git)
$planetsDirWithoutGit = "/pathto/php/projects/universe-snapshots/planets"; // exportable planets dir (without .git)
$destDir = __DIR__ . "/../public/class-planets";


//--------------------------------------------
// SCRIPT
//--------------------------------------------
if ("cli" !== php_sapi_name()) {
    throw new \Exception("Please call this script from command line");
}


//--------------------------------------------
// PARSE INPUT
//--------------------------------------------
$options = getopt("d");
$mode = "symlink";
$sourceDir = $planetsDirWithGit;
if (array_key_exists("d", $options)) {
    $mode = "dir";
    $sourceDir = $planetsDirWithoutGit;
}


//--------------------------------------------
// DO THE MAIN LOOP...
//--------------------------------------------
if (file_exists($destDir)) {
    if (file_exists($sourceDir)) {
        $files = file($destDir . "/_import.txt", \FILE_IGNORE_NEW_LINES);

        foreach ($files as $dir) {


            $oldFile = $destDir . "/$dir";

            if (file_exists($oldFile)) {
                if (is_link($oldFile)) {
                    unlink($oldFile);
                } else {
                    FileSystemTool::remove($oldFile);
                }
            }


            $planetDir = $sourceDir . "/$dir";

            if ('symlink' === $mode) {
                symlink($planetDir, $oldFile);
            } else { // dir
                FileSystemTool::copyDir($planetDir, $oldFile);
            }
        }
    } else {
        throw new \Exception("planets directory does not exist: $sourceDir");
    }

} else {
    throw new \Exception("destination directory does not exist: $destDir");
}