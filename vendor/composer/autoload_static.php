<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1fa2cefe1161b0fbbd7067d4ea6672a1
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'SkyNetFront\\' => 12,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'SkyNetFront\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
        'PhpOption\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1fa2cefe1161b0fbbd7067d4ea6672a1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1fa2cefe1161b0fbbd7067d4ea6672a1::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
