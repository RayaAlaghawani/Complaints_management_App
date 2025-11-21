<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class HelperMakeCommand extends GeneratorCommand
{
    protected $signature = 'make:helper {name}';
    protected $description = 'Create a new Helper class';

    protected function getStub()
    {
        return __DIR__ . '/stubs/helper.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Helpers';
    }
}