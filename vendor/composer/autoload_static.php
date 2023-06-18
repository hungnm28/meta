<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2b00b16e451633c01ae2150ab1208bb3
{
    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'Hungnm28\\Meta\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Hungnm28\\Meta\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2b00b16e451633c01ae2150ab1208bb3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2b00b16e451633c01ae2150ab1208bb3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2b00b16e451633c01ae2150ab1208bb3::$classMap;

        }, null, ClassLoader::class);
    }
}
