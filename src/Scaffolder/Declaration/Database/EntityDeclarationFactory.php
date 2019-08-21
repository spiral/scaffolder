<?php
/**
 * Spiral Framework.
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\Database;

use Spiral\Core\FactoryInterface;
use Spiral\Scaffolder\Config\ScaffolderConfig;

class EntityDeclarationFactory
{
    /** @var ScaffolderConfig */
    private $config;
    /** @var FactoryInterface */
    private $factory;

    public function __construct(ScaffolderConfig $config, FactoryInterface $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    public function create(string $element, string $type): AbstractEntityDeclaration
    {
        return $this->factory->make($this->config->declarationOptions($element)[$type]);
    }
}