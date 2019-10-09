<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Tests\Scaffolder\Command;

use Spiral\Console\Console;
use Spiral\Files\FilesInterface;
use Spiral\Tests\Scaffolder\BaseTest;

abstract class AbstractCommandTest extends BaseTest
{
    /**
     * @param string $class
     */
    protected function deleteDeclaration(string $class): void
    {
        if (class_exists($class)) {
            try {
                $reflection = new \ReflectionClass($class);
                $this->files()->delete($reflection->getFileName());
            } catch (\Throwable $exception) {
            }
        }
    }

    protected function console(): Console
    {
        return $this->app->get(Console::class);
    }

    protected function files(): FilesInterface
    {
        return $this->app->get(FilesInterface::class);
    }
}
