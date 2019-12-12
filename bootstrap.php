<?php

// Errors

function printError($errno, $errstr, $errfile, $errline, $errcontext) {
    http_response_code(500);

    ?>
        <style>
            body {
                background-color: #640822;
                color: #ffffff;
            }
            .pale {
                opacity: 0.65;
            }
        </style>
        <div style="padding: 16px 20px; margin-bottom: 8px; font: 14px monospace; background-color: #7c0023;">
            <h1 style="margin-top: 0;">Error #<?php echo $errno; ?>: <?php echo $errstr; ?></h1>
            <p><span class="pale">File:</span> <?php echo $errfile; ?> on line <strong><?php echo $errline; ?></strong></p>
            <hr class="pale" style="height: 1px; margin: 20px 0; border: none; background-color: #ffffff;">
            <h3>Context:</h3>
            <pre><?php echo json_encode($errcontext, JSON_PRETTY_PRINT); ?></pre>
        </div>
    <?php
}

function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
    ob_end_clean();
    printError($errno, $errstr, $errfile, $errline, $errcontext);
}

function exceptionHandler($exception) {
    ob_end_clean();

    if (is_a($exception, 'SkyNetFront\Core\HTTP\HTTPException')) {
        if ($exception->hasView()) {
            echo $exception->getView()->render();
        }

        return;
    }

    printError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace());
}

set_error_handler('errorHandler', error_reporting());
set_exception_handler('exceptionHandler');

// Config

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['ENV_DIR']);
$dotenv->load();

// Locale

$locale = getenv('LOCALE');

if (defined('LC_MESSAGES')) {
    setlocale(LC_MESSAGES, $locale); // Linux
    bindtextdomain('messages', __DIR__ . '/locale');
} else {
    putenv("LC_ALL={$locale}"); // windows
    bindtextdomain('messages', __DIR__ . '\locale');
}

textdomain("messages");

// Define router rules

$router = new \SkyNetFront\Core\Router();

$router->rule('index', [
    'controller' => 'Index',
    'action' => 'index',
]);

// Run the App
$router->process();
