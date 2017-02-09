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
     * @param string $alias
     * @param string $comment
     */
    public function __construct($name, $alias, $comment = '')
    {
        parent::__construct($name, 'Command', [], $comment);

        $this->declareStructure();
        $this->setAlias($alias);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            Command::class => null
        ];
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setAlias($name)
    {
        return $this->property('name')->setDefault($name);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->property('name')->getDefault();
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->property('description')->setDefault($description);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->property('description')->getDefault();
    }

    /**
     * @return MethodDeclaration
     */
    public function performMethod()
    {
        return $this->method('perform');
    }

    /**
     * Declare default __invoke method body.
     */
    private function declareStructure()
    {
        $invoke = $this->method('perform')->setAccess(MethodDeclaration::ACCESS_PROTECTED);
        $invoke->comment()->setString("Perform command");

        $protected = ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED;

        $this->property('name')->setAccess($protected);
        $this->property('name')->setComment('@var string')->setDefault('');

        $this->property('description')->setAccess($protected);
        $this->property('description')->setComment('@var string')->setDefault('');

        $this->property('arguments')->setAccess($protected);
        $this->property('arguments')->setComment('@var array')->setDefault([]);

        $this->property('options')->setAccess($protected);
        $this->property('options')->setComment('@var array')->setDefault([]);
    }
}
