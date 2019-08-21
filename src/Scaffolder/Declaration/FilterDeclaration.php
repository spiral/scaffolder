<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration;

use Spiral\Filters\Filter;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class FilterDeclaration extends ClassDeclaration implements DependedInterface
{
    /** @var array */
    private $mapping;

    /**
     * @param string $name
     * @param string $comment
     * @param array  $mapping
     */
    public function __construct(string $name, string $comment = '', array $mapping = [])
    {
        parent::__construct($name, 'Filter', [], $comment);
        $this->mapping = $mapping;

        $this->declareStructure();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [Filter::class => null];
    }

    /**
     * Add new field to request and generate default filters and validations if type presented in mapping.
     *
     * @param string $field
     * @param string $type
     * @param string $source
     * @param string $origin
     */
    public function declareField(string $field, string $type, string $source, string $origin = null): void
    {
        $schema = $this->constant('SCHEMA')->getValue();
        $validates = $this->constant('VALIDATES')->getValue();

        if (!isset($this->mapping[$type])) {
            $schema[$field] = $source . ':' . ($origin ?: $field);

            $this->constant('SCHEMA')->setValue($schema);

            return;
        }

        $definition = $this->mapping[$type];

        //Source can depend on type
        $source = $definition['source'];
        $schema[$field] = $source . ':' . ($origin ?: $field);

        if (!empty($definition['validates'])) {
            //Pre-defined validation
            $validates[$field] = $definition['validates'];
        }

        $this->constant('SCHEMA')->setValue($schema);
        $this->constant('VALIDATES')->setValue($validates);
    }

    /**
     * Declare filter structure.
     */
    protected function declareStructure(): void
    {
        $this->constant('SCHEMA')->setPublic()->setValue([]);
        $this->constant('VALIDATES')->setPublic()->setValue([]);
    }
}
