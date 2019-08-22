<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace TestApp;

use Spiral\Boot;
use Spiral\Migrations;
use Spiral\Scaffolder;

class TestApp extends Boot\AbstractKernel
{
    protected const LOAD = [
        Scaffolder\Bootloader\ScaffolderBootloader::class
    ];

    /**
     * {@inheritDoc}
     */
    protected function bootstrap(): void
    {
        $this->container->bind(Migrations\RepositoryInterface::class, Migrations\FileRepository::class);
    }

    /**
     * Normalizes directory list and adds all required aliases.
     *
     * @param array $directories
     * @return array
     */
    protected function mapDirectories(array $directories): array
    {
        if (!isset($directories['root'])) {
            throw new Boot\Exception\BootException('Missing required directory `root`.');
        }

        if (!isset($directories['app'])) {
            $directories['app'] = $directories['root'] . '/app/';
        }

        return array_merge([
            'vendor'  => $directories['root'] . '/vendor/',
            'runtime' => $directories['root'] . '/runtime/',
            'config'  => $directories['app'] . '/config/'
        ], $directories);
    }

    public function get(string $target)
    {
        return $this->container->get($target);
    }

    public function directory(string $directory): string
    {
        /** @var Boot\DirectoriesInterface $directories */
        $directories = $this->container->get(Boot\DirectoriesInterface::class);

        return $directories->get($directory);
    }
}