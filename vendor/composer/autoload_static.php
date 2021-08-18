<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit188e6212f68420bb05cdbd0e5e34d57d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Predis\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Predis\\' => 
        array (
            0 => __DIR__ . '/..' . '/predis/predis/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit188e6212f68420bb05cdbd0e5e34d57d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit188e6212f68420bb05cdbd0e5e34d57d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit188e6212f68420bb05cdbd0e5e34d57d::$classMap;

        }, null, ClassLoader::class);
    }
}