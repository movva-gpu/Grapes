<?php

/**
 * @param string $path - Please use `/` not `\\`
 * @return string - If on windows, `folder/file` will be turned into `folder\file`
 */
function separatesPath(string $path): string
{
    return implode(DIRECTORY_SEPARATOR, explode('/', $path));
}

/**
 * @param string $path - Path to a file in the root dir.
 * @return string - Absolute path to that file. (even if it doesn't exist).
 */
function rootPath(string $path): string
{
    return __ROOT__ . DIRECTORY_SEPARATOR . separatesPath($path);
}

/**
 * @param string $path - Path to a file in the views' directory.
 * @return string - Absolute path to that file. (even if it doesn't exist).
 */
function viewsPath(string $path): string
{
    return rootPath('views/' . $path);
}

/**
 * @param string $path Path to a file relative to the assets directory
 * @return string If a minified version of the file is found in dist/, then it outputs
 * (if it exists). Else, if it exists in dist/, then it outputs, else, it will take the one in the
 * assets/ directory.
 */
function getAsset(string $path): string
{
    $fileAssetsPath = 'assets/' . $path;
    $fileAssetsPathExists = file_exists(rootPath($fileAssetsPath));

    $fileDistPath = 'dist/' . $path;
    $fileDistPathExists = file_exists(rootPath($fileDistPath));

    $fileExtension = explode('.', $path)[1];
    $fileNameOrPath = explode('.', $path)[0];
    $minifiedFilename = $fileNameOrPath . '.min.' . $fileExtension;
    $minifiedFilePath = 'dist/' . $minifiedFilename;
    $minifiedFilePathExists = file_exists(rootPath($minifiedFilePath));

    return $minifiedFilePathExists
        ? $minifiedFilePath
        : ($fileDistPathExists
            ? $fileDistPath
            : ($fileAssetsPathExists
                ? $fileAssetsPath
                : throw new InvalidArgumentException('Asset ' . $path . ' not found.', 404)));
}
