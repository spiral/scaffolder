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
use Spiral\Reactor\DependedInterface;

class ServiceDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct(string $name, string $comment = null)
    {
        parent::__construct($name, 'Service', [], (string)$comment);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [Service::class => null];
    }
}