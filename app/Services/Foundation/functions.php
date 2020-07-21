<?php

if (!function_exists('sanitize_url')) {
    /**
     * Sanitize URL. Add trailing slash.
     *
     */
    function sanitize_url($url, $slug = '')
    {
        return rtrim($url, '/') . '/' . ltrim($slug, '/');
    }
}

if (!function_exists('array_keys_exists')) {
    /**
     * compare if all keys exist
     */
    function array_keys_exists(array $keys, array $compare)
    {
        return !array_diff_key(array_flip($keys), $compare);
    }
}

if (!function_exists('check_production')) {
    /**
     * @return bool
     */
    function check_production()
    {
        if (config('app.env') === 'production' && config('app.debug') === false) {
            return true;
        }

        return false;
    }
}

if (!function_exists('make_sqlite_files')) {
    function make_sqlite_files()
    {
        if (!file_exists(database_path('stub.sqlite'))) {
            $file = fopen(database_path('stub.sqlite'), 'w');
            fclose($file);
        }

        if (!file_exists(database_path('testing.sqlite'))) {
            $file = fopen(database_path('testing.sqlite'), 'w');
            fclose($file);
        }
    }
}

if (!function_exists('generate_random_string')) {
    function generate_random_string($length = 8)
    {
        return substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ._ ', ceil($length/strlen($x)))), 1, $length);
    }
}

/**
 * Check if two files are identical.
 *
 * If you just need to find out if two files are identical, comparing file
 * hashes can be inefficient, especially on large files.  There's no
 * reason to read two whole files and do all the math if the
 * second byte of each file is different.  If you don't need to
 * store the hash value for later use, there may not be a need to
 * calculate the hash value just to compare files.This can be much faster.
 *
 * @link http://www.php.net/manual/en/function.md5-file.php#94494
 *
 * @param string $fileOne
 * @param string $fileTwo
 * @return boolean
 */
if (!function_exists('identical')) {
    function identical($fileOne, $fileTwo)
    {
        if (filetype($fileOne) !== filetype($fileTwo)) return false;
        if (filesize($fileOne) !== filesize($fileTwo)) return false;

        if (! $fp1 = fopen($fileOne, 'rb')) return false;

        if (! $fp2 = fopen($fileTwo, 'rb'))
        {
            fclose($fp1);
            return false;
        }

        $same = true;

        while (! feof($fp1) and ! feof($fp2))
            if (fread($fp1, 4096) !== fread($fp2, 4096))
            {
                $same = false;
                break;
            }

        if (feof($fp1) !== feof($fp2)) $same = false;

        fclose($fp1);
        fclose($fp2);

        return $same;
    }
}
