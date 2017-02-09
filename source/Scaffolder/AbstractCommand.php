<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder;

use Interop\Container\ContainerInterface;
use Spiral\Console\Command;
use Spiral\Core\FactoryInterface;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\FileDeclaration;
use Spiral\Scaffolder\Configs\ScaffolderConfig;

abstract class AbstractCommand extends Command
{
    /**
     * Element to be managed.
     */
    const ELEMENT = '';

    /**
     * @var ScaffolderConfig
     */
    protected $config;

    /**
     * @var FilesInterface
     */
    protected $files;

    /**
     * @param ScaffolderConfig   $config
     * @param FilesInterface     $files
     * @param ContainerInterface $container
     */
    public function __construct(
        ScaffolderConfig $config,
        FilesInterface $files,
        ContainerInterface $container
    ) {
        $this->config = $config;
        $this->files = $files;
        parent::__construct($container);
    }

    /**
     * @param array $parameters
     *
     * @return ClassDeclaration
     */
    protected function createDeclaration(array $parameters = []): ClassDeclaration
    {
        return $this->container->get(FactoryInterface::class)->make(
            $this->config->declarationClass(static::ELEMENT),
            [
                'name'    => $this->getClass(),
                'comment' => $this->option('comment')
            ] + $parameters + $this->config->declarationOptions(static::ELEMENT)
        );
    }

    /**
     * Get class name of element being rendered.
     *
     * @return string
     */
    protected function getClass(): string
    {
        return $this->config->className(
            static::ELEMENT,
            $this->argument('name')
        );
    }

    /**
     * Get namespace of element being rendered.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return $this->config->classNamespace(
            static::ELEMENT,
            $this->argument('name')
        );
    }

    /**
     * Write declaration into file.
     *
     * @param ClassDeclaration $declaration
     */
    protected function writeDeclaration(ClassDeclaration $declaration)
    {
        $filename = $this->config->classFilename(
            static::ELEMENT,
            $this->argument('name')
        );

        $filename = $this->files->normalizePath($filename);

        if ($this->files->exists($filename)) {
            $this->writeln(
                "<fg=red>Unable to write '<comment>{$declaration->getName()}</comment>' declaration, "
                . "file '<comment>{$filename}</comment>' already exists.</fg=red>"
            );

            return;
        }

        $file = new FileDeclaration($this->getNamespace(), $this->config->headerLines());
        $file->addElement($declaration);

        $this->files->write(
            $filename,
            $file->render(),
            FilesInterface::READONLY,
            true
        );

        $this->writeln(
            "Declaration of '<info>{$declaration->getName()}</info>' "
            . "has been successfully written into '<comment>{$filename}</comment>'."
        );
    }
}