<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0cfd7a504d2896f556a9a3fce18d1426
{
    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'Endroid' => 
            array (
                0 => __DIR__ . '/..' . '/endroid/qrcode/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit0cfd7a504d2896f556a9a3fce18d1426::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
