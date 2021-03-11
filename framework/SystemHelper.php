<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;


if (!function_exists('env')) {
    /**
     * Fetches values from env file
     * @param $key
     * @param string $default
     * @return array|false|string
     */
    function env($key, $default = '')
    {
        static $variables;

        //TODO: Changes flag in env to reload its value.

        if ($variables === null) {
            $variables = Dotenv::createImmutable(base_directory());
            $variables->safeLoad();
        }

        return (!empty(getenv($key)) && getenv($key) != '') ? getenv($key) : $default;
    }
}

if (!function_exists('config')) {
    /**
     * Parse config files anf fetch variables
     * @param null $key
     * @param null $default
     * @return mixed|void|null
     * @throws Exception
     */
    function config($key = null, $default = null)
    {
        $data = null;

        $base_directory = base_directory() . '/config/';

        $keys = explode('.', $key);
        $keyLength = count($keys);

        if ($keyLength > 1) {
            $file = $base_directory . $keys[0] . '.php';
            if (file_exists($file)) {
                $data = include $file;

                //Parse into the array structure of file
                for ($i = 1; $i < $keyLength; $i++) {
                    $data = $data[$keys[$i]];
                }
            } else {
                //throw new \App\Exceptions\GeneralException('asdasdasdasdsa');
                throw new Exception('Config : ' . $file . ' file does not exists.');
            }
        } else {
            return;
        }
        return $data;
    }
}

if (!function_exists('include_route_files')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new RecursiveDirectoryIterator($folder);
            $it = new RecursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('class_autoloader')) {
    /**
     * Function to locate and autoload the class with spl_autoload_register function
     * @param $class
     * @throws Exception
     */
    function class_autoloader($class)
    {
        $prefix = 'App\\';

        $length = strlen($prefix);

        $base_directory = base_directory() . '/app/';

        //echo $prefix.', '.$class.', '. $length;//die;

        if (strncmp($prefix, $class, $length) !== 0) {
            throw new Exception('Namespace does not exists.');
            return;
        }

        $class_end = substr($class, $length);

        //echo $class.', '.$class_end.', '.$base_directory;

        $file = $base_directory . str_replace('\\', '/', $class_end) . '.php';

        echo 'File included : ' . $file . '<br/>';//die;

        if (file_exists($file)) {
            require $file;
        } else {
            throw new Exception('Class : ' . $class . ' does not exists.');
        }
    }
}

if (!function_exists('helper_autoloader')) {
    /**
     * Function to locate and autoload the class with spl_autoload_register function
     * @param $class
     */
    function helper_autoloader($class)
    {

    }
}

if (!function_exists('global_exception_handler')) {
    function global_exception_handler($exception)
    {
        $e = new \App\Exceptions\Handler($exception);
        $logger = new Logger('GLOBAL');
        $logger->pushHandler(new StreamHandler(base_directory() . '/storage/logs/app.log', Logger::DEBUG));
        $logger->error('Oh no an exception happened! ');

        //this code should log the exception to disk and an error tracking system
        echo "Global Exception: " . $exception->getMessage();
        die;
    }
}

if (!function_exists('pp')) {
    function pp($data, $die = 1)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        if ($die == 1) {
            die('Pretty Print Finished.');
        }
    }
}

if (!function_exists('dd')) {
    function dd($data, $die = 1)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        if ($die == 1) {
            die('Var Dump Finished.');
        }
    }
}