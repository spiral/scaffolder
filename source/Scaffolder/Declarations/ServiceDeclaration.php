<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Declarations;

use Spiral\Core\Service;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\UseRequesterInterface;

class ServiceDeclaration extends ClassDeclaration implements UseRequesterInterface
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct($name, $comment = '')
    {
        parent::__construct($name, 'Service', [], $comment);
    }

    /**
     * {@inheritdoc}
     */
    public function requestsUses()
    {
        return [
            Service::class => null
        ];
    }
}