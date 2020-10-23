<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3f26da2f7367b688117fed70113b986c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3f26da2f7367b688117fed70113b986c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3f26da2f7367b688117fed70113b986c::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInit3f26da2f7367b688117fed70113b986c::$fallbackDirsPsr4;

        }, null, ClassLoader::class);
    }
}
