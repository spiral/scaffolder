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
    public function __construct($name, $comment = '')
    {
        parent::__construct($name, 'Controller', [], $comment);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            Controller::class => null
        ];
    }

    /**
     * @param string $action
     * @return ClassDeclaration\MethodDeclaration
     */
    public function action($action)
    {
        $method = $this->method($action . $this->actionPostfix);

        return $method->setAccess(ClassDeclaration\MethodDeclaration::ACCESS_PROTECTED);
    }
}