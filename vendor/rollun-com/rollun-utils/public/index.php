<?php

// Delegate static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
require_once 'config/env_configurator.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
call_user_func(function () {
    /** @var \Interop\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    //localhost:8080/{module-name}/{example-name}
    $path = $_SERVER["REQUEST_URI"];
    if(preg_match('/(?<module>[\w]+)\/(?<example>[\w\W]+)/', $path, $match)) {
        $module =  $match["module"];
        $example =  $match["example"];
        $scriptPath = "src/{$module}/src/Example/{$example}.php";
        $scriptPath = str_replace("/", DIRECTORY_SEPARATOR, $scriptPath);
        if(file_exists($scriptPath)){
            include $scriptPath;
        }else {
            throw new InvalidArgumentException("Not found example $example in module $module");
        }
    }
});
