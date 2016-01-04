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

class AbstractCommand extends Command
{
    /**
     * Element to be managed.
     */
    const ELEMENT = '';

    /**
     * @var ScaffolderConfig
     */
    protected $config = null;

    /**
     * @param ScaffolderConfig   $config
     * @param ContainerInterface $container
     */
    public function __construct(ScaffolderConfig $config, ContainerInterface $container)
    {
        $this->config = $config;
        parent::__construct($container);
    }

    /**
     * @param array $parameters
     * @return ClassDeclaration
     */
    protected function createDeclaration($parameters = [])
    {
        return $this->container->get(FactoryInterface::class)->make(
            $this->config->declarationClass(static::ELEMENT),
            [
                'name'    => $this->elementClass(),
                'comment' => $this->option('comment')
            ] + $parameters
        );
    }

    /**
     * @return string
     */
    protected function elementClass()
    {
        return $this->config->elementName(static::ELEMENT, $this->argument('name'));
    }

    /**
     * @return string
     */
    protected function elementNamespace()
    {
        return $this->config->elementNamespace(static::ELEMENT, $this->argument('name'));
    }

    /**
     * @param ClassDeclaration $declaration
     */
    protected function writeDeclaration(ClassDeclaration $declaration)
    {
        $filename = $this->config->elementFilename(static::ELEMENT, $this->argument('name'));
        $filename = $this->files->normalizePath($filename);

        if ($this->files->exists($filename)) {
            $this->writeln(
                "<fg=red>Unable to write '<comment>{$declaration->getName()}</comment>' declaration, "
                . "file '<comment>{$filename}</comment>' already exists.</fg=red>"
            );

            return;
        }

        $file = new FileDeclaration($this->elementNamespace(), $this->config->headerLines());
        $file->addElement($declaration);

        $this->files->write($filename, $file->render(), FilesInterface::READONLY, true);

        $this->writeln(
            "Declaration of '<info>{$declaration->getName()}</info>' "
            . "has been successfully written into '<comment>{$filename}</comment>'."
        );
    }
}