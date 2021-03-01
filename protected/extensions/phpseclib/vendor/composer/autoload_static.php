<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba3e9ca538c9191a54d3991707687e7e
{
    public static $files = array (
        'decc78cc4436b1292c6c0d151b19445c' => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitba3e9ca538c9191a54d3991707687e7e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba3e9ca538c9191a54d3991707687e7e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitba3e9ca538c9191a54d3991707687e7e::$classMap;

        }, null, ClassLoader::class);
    }
}
