<?php

/**
 * @param string $path Please use `/` not `\`
 * @return string If on windows, `folder/file` will be turned into `folder\file`
 */
function separate_path(string $path): string
{
    return implode(DIRECTORY_SEPARATOR, explode('/', $path));
}

/**
 * @param string $path Path to a file in the root dir. Please use `/` not `\`
 * @return string Absolute path to that file. (even if it doesn't exist).
 */
function root_path(string $path): string
{
    return __ROOT__ . DIRECTORY_SEPARATOR . separate_path($path);
}

/**
 * @param string $path Path to a file in the views' directory. Please use `/` not `\`
 * @return string Absolute path to that file. (even if it doesn't exist).
 */
function views_path(string $path): string
{
    return root_path('views/' . $path);
}

/**
 * @param string $path Path to a file relative to the assets directory. Please use `/` not `\`
 * @return string If a minified version of the file is found in dist/, then it outputs
 * (if it exists). Else, if it exists in dist/, then it outputs, else, it will take the one in the
 * assets/ directory.
 */
function assets_path(string $path): string
{
    $file_assets_path = 'assets/' . $path;

    $file_dist_path = 'dist/' . $path;

    $file_extension = pathinfo($path, PATHINFO_EXTENSION);
    $file_name_or_path = pathinfo($path, PATHINFO_FILENAME);
    $minified_filename = $file_name_or_path . '.min.' . $file_extension;
    $minified_file_path = 'dist/' . $minified_filename;

    if (file_exists(root_path($minified_file_path)))
    {
        return SITE_URL . '/' . $minified_file_path;
    } elseif (file_exists(root_path($file_dist_path)))
    {
        return SITE_URL . '/' . $file_dist_path;
    } elseif (file_exists(root_path($file_assets_path)))
    {
        return SITE_URL . '/' . $file_assets_path;
    }

    throw new InvalidArgumentException('Asset ' . $path . ' not found.', 404);
}

/**
 * Get all subdirectories
 * @return array
 */
function get_subdirectories(string $directory): array
{
    return glob(root_path($directory . '/*'), GLOB_ONLYDIR);
}

/**
 * Parses the URL and returns only its path.
 *
 * @param string $url The URL to parse.
 * @return string The path of the URL.
 */
function parse_url_path(string $url): string
{
    return \Safe\parse_url($url)['path'];
}
