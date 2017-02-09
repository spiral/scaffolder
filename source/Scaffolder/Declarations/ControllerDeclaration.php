<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Declarations;

use Spiral\Core\Controller;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

/**
 * Declares controller.
 */
class ControllerDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * @var string
     */
    private $actionPostfix = 'Action';

    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct(string $name, string $comment = null)
    {
        parent::__construct($name, 'Controller', [], (string)$comment);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [Controller::class => null];
    }

    /**
     * @param string $action
     *
     * @return ClassDeclaration\MethodDeclaration
     */
    public function addAction(string $action): ClassDeclaration\MethodDeclaration
    {
        $method = $this->method($action . $this->actionPostfix);

        return $method->setAccess(ClassDeclaration\MethodDeclaration::ACCESS_PROTECTED);
    }
}