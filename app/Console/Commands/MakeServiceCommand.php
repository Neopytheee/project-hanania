<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeServiceCommand extends GeneratorCommand
{
    // Ini perintah yang akan Anda panggil di terminal
    protected $signature = 'make:service {name}';

    protected $description = 'Buat Service Class baru untuk Business Logic';

    protected $type = 'Service';

    // Mengarahkan ke template stub bawaan Laravel untuk menghemat waktu
    protected function getStub()
    {
        return __DIR__.'/../../../vendor/laravel/framework/src/Illuminate/Foundation/Console/stubs/class.stub';
    }

    // Mengatur agar file otomatis masuk ke folder app/Services
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }
}
