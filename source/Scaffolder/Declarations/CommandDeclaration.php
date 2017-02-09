<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Declarations;

use Spiral\Console\Command;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\ClassDeclaration\MethodDeclaration;
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
    public function setAlias(string $name)
    {
        $this->constant('NAME')->setValue($name);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->constant('NAME')->getValue();
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->constant('DESCRIPTION')->setValue($description);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->constant('DESCRIPTION')->getValue();
    }

    /**
     * @return MethodDeclaration
     */
    public function performMethod(): MethodDeclaration
    {
        return $this->method('perform');
    }

    /**
     * Declare default __invoke method body.
     */
    private function declareStructure()
    {
        $perform = $this->method('perform')->setAccess(MethodDeclaration::ACCESS_PROTECTED);
        $perform->setComment("Perform command");

        $this->constant('NAME');
        $this->constant('NAME')->setComment('@var string')->setValue('');

        $this->constant('DESCRIPTION');
        $this->constant('DESCRIPTION')->setComment('@var string')->setValue('');

        $this->constant('ARGUMENTS');
        $this->constant('ARGUMENTS')->setComment('@var array')->setValue([]);

        $this->constant('OPTIONS');
        $this->constant('OPTIONS')->setComment('@var array')->setValue([]);
    }
}
