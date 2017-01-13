<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita4db40e21c09e0f9ca16326720c217a7
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Redshop\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Redshop\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'D' => 
        array (
            'Doctrine\\Common\\Lexer\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/lexer/lib',
            ),
            'Doctrine\\Common\\Inflector\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/inflector/lib',
            ),
            'Doctrine\\Common\\Annotations\\' => 
            array (
                0 => __DIR__ . '/..' . '/doctrine/annotations/lib',
            ),
        ),
        'B' => 
        array (
            'Behat\\Transliterator' => 
            array (
                0 => __DIR__ . '/..' . '/behat/transliterator/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita4db40e21c09e0f9ca16326720c217a7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita4db40e21c09e0f9ca16326720c217a7::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInita4db40e21c09e0f9ca16326720c217a7::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
