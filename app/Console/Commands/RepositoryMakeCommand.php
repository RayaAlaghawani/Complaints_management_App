<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class RepositoryMakeCommand  extends GeneratorCommand
{
    protected $signature = 'make:repository  {name}';

    protected $description = 'Create a new Repository class';

    protected function getStub()
    {
        return __DIR__ . '/stubs/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Repositories';
    }
}
