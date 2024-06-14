<?php declare(strict_types = 1);
error_reporting(E_ALL);

const __ROOT__ = __DIR__ . DIRECTORY_SEPARATOR . '..';

require_once __ROOT__ . DIRECTORY_SEPARATOR .
    'utils' . DIRECTORY_SEPARATOR .
    'autoload.php';
include root_path('vendor/autoload.php');

# To run me, run `npm run win:workers` or `npm run uni:workers`

while (true)
{
    $queue_file = root_path('queue.txt');
    if (file_exists($queue_file))
    {
        echo timestamp() . "queue.txt found!\r\n";
        $queue = \Safe\file($queue_file, FILE_IGNORE_NEW_LINES);
        $queue = array_filter($queue, function(?string $line)
        {
            return !is_null($line) && !empty($line);
        });
        if (!empty($queue))
        {
            $queue_length = count($queue);
            for ($i = 0; $i <= $queue_length; $i++)
            {
                $file_path = array_shift($queue);
                if (is_null($file_path))
                {
                    break;
                }
                
                for ($y = 0; $y < 5; $y++)
                {
                    if (compress_image_to_avif($file_path))
                    {
                        echo timestamp() . "Compressed $file_path successfully\r\n";
                        \Safe\file_put_contents($queue_file, implode(PHP_EOL, $queue));
                    } else
                    {
                        echo timestamp() . "Failed to compress image to AVIF: $file_path\r\n";
                        $queue[] = $file_path;
                    }
                }
            }
        } else {
            $uploads_dir = \Safe\glob(root_path('assets/uploads/*'));
            if (!empty($uploads_dir))
            {
                echo timestamp() . "cleaning uploads...\r\n";
                foreach ($uploads_dir as $key => $value) {
                    \Safe\unlink($value);
                }
            } else
            {
                echo timestamp() . "empty file, waiting 5 seconds...\r\n";
            }
        }
    }
    sleep(5);
}

function compress_image_to_avif(string $file_path): bool
{
    $resolutions = [24, 64, 92, 128, 256, 1024];
    $file_info = pathinfo($file_path);

    foreach ($resolutions as $res)
    {
        $output_file = root_path('assets/pfp/' . $file_info['filename'] . '_' . $res . 'px.avif');

        $out        = null;
        $return_var = null;
        $command    = 'magick ' . $file_path .
            ' -resize ' . 'x' . $res .
            ' -quality 95' . ' -gravity Center -crop ' . $res . 'x' . $res .
            ' ' . $output_file;

        echo $command;
        
        \Safe\exec($command, $out, $return_var);

        if (!empty($out))
        {
            echo timestamp() . "ImageMagick out:\r\n$out\r\n";
        }

        if ($return_var !== 0)
        {
            return false;
        }
    }

    return true;
}
