<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class FactoryMakeCommand extends GeneratorCommand
{
    protected $signature = 'make:factory {name}';
    protected $description = 'Create a new Factory class';

    protected function getStub()
    {
        return __DIR__ . '/stubs/factory.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Factories';
    }
}
