<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration;

use Spiral\Console\Command;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class CommandDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct(string $name, string $comment = '')
    {
        parent::__construct($name, 'Command', [], $comment);

        $this->declareStructure();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [Command::class => null];
    }

    /**
     * Set command alias.
     *
     * @param string $name
     */
    public function setAlias(string $name): void
    {
        $this->constant('NAME')->setValue($name);
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->constant('NAME')->getValue();
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->constant('DESCRIPTION')->setValue($description);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->constant('DESCRIPTION')->getValue();
    }

    /**
     * Declare default command body.
     */
    private function declareStructure(): void
    {
        $perform = $this->method('perform')->setProtected();
        $perform->setReturn('void');
        $perform->setComment('Perform command');

        $this->constant('NAME')->setPublic()->setValue('');
        $this->constant('DESCRIPTION')->setPublic()->setValue('');
        $this->constant('ARGUMENTS')->setPublic()->setValue([]);
        $this->constant('OPTIONS')->setPublic()->setValue([]);
    }
}
