<?php

/**
 * Finds the appropriate controller directory based on the page name
 * @param $page_ame string
 * @return string|null
 */
function find_controller_directory(string $page_ame): ?string
{
    $directories = get_subdirectories('controllers');

    foreach ($directories as $directory)
    {
        if (directory_contains_page($directory, $page_ame))
        {
            return $directory;
        }
    }

    handle_404_error();
}

/**
 * Check if a directory contains a page with the given name
 * @param string $directory
 * @param string $page_ame
 * @return bool
 */
function directory_contains_page(string $directory, string $page_ame): bool
{
    $dirname = basename($directory);

    foreach (explode('+', $dirname) as $route)
    {
        if ($route === $page_ame && controller_file_exists($directory))
        {
            $GLOBALS['current_dir'] = $dirname;
            return true;
        }
    }

    return false;
}

/**
 * Check if the controller file exists
 * @param string $directory
 * @return bool
 */
function controller_file_exists(string $directory): bool
{
    return file_exists(separate_path($directory . '/controller.php'));
}

/**
 * Handle 404 error
 */
function handle_404_error(): void
{
    header('Location: /404', true);
    exit;
}
