<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class BootloaderDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct(string $name, string $comment = '')
    {
        parent::__construct($name, 'Bootloader', [], $comment);

        $this->declareStructure();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            Bootloader::class => null
        ];
    }

    /**
     * Declare constants and boot method.
     */
    private function declareStructure(): void
    {
        $this->constant('BINDINGS')->setPublic()->setValue([]);
        $this->constant('SINGLETONS')->setPublic()->setValue([]);
        $this->constant('DEPENDENCIES')->setPublic()->setValue([]);

        $method = $this->method('boot');
        $method->setPublic();
        $method->setReturn('void');
    }
}