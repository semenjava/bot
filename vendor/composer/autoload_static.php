<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit372288908c30e2db0448f6e698105ff1
{
    public static $files = array (
        '357b222fc099f9a5b2513092e0d29b82' => __DIR__ . '/../..' . '/config.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/classes',
        ),
    );

    public static $prefixesPsr0 = array (
        'V' => 
        array (
            'View\\' => 
            array (
                0 => __DIR__ . '/../..' . '/lib/view',
            ),
        ),
        'S' => 
        array (
            'Sunra\\PhpSimple\\HtmlDomParser' => 
            array (
                0 => __DIR__ . '/..' . '/sunra/php-simple-html-dom-parser/Src',
            ),
        ),
        'K' => 
        array (
            'KubAT\\PhpSimple\\HtmlDomParser' => 
            array (
                0 => __DIR__ . '/..' . '/kub-at/php-simple-html-dom-parser/src',
            ),
        ),
        'H' => 
        array (
            'Http\\' => 
            array (
                0 => __DIR__ . '/../..' . '/lib/http',
            ),
        ),
        'A' => 
        array (
            'Assetic' => 
            array (
                0 => __DIR__ . '/..' . '/kriswallsmith/assetic/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit372288908c30e2db0448f6e698105ff1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit372288908c30e2db0448f6e698105ff1::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit372288908c30e2db0448f6e698105ff1::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
