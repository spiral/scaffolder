<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace TestApp;

use Spiral\Boot\AbstractKernel;
use Spiral\Boot\Exception\BootException;
use Spiral\Scaffolder\Bootloader\ScaffolderBootloader;

class TestApp extends AbstractKernel
{
    protected const LOAD = [
        ScaffolderBootloader::class
    ];

    /**
     * {@inheritDoc}
     */
    protected function bootstrap(): void
    {
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
            throw new BootException('Missing required directory `root`.');
        }

        if (!isset($directories['app'])) {
            $directories['app'] = $directories['root'] . '/app/';
        }

        return array_merge([
            'vendor' => $directories['root'] . '/vendor/',
            'config' => $directories['app'] . '/config/',
        ], $directories);
    }

    public function get(string $target)
    {
        return $this->container->get($target);
    }
}