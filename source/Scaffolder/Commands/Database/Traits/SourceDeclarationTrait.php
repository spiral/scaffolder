<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Scaffolder\Commands\Database\Traits;

use Spiral\Scaffolder\Configs\ScaffolderConfig;
use Spiral\Scaffolder\Declarations\Database\SourceDeclaration;

trait SourceDeclarationTrait
{
    /**
     * @param string $name
     * @param string $type
     * @param string $model
     *
     * @return \Spiral\Scaffolder\Declarations\Database\SourceDeclaration
     */
    private function sourceDeclaration(string $name, string $type, string $model): SourceDeclaration
    {
        return new SourceDeclaration(
            $this->getConfig()->className('source', $name),
            $type,
            $model
        );
    }

    /**
     * @return \Spiral\Scaffolder\Configs\ScaffolderConfig
     */
    abstract protected function getConfig(): ScaffolderConfig;
}