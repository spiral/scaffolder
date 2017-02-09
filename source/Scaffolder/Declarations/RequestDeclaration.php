<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Declarations;

use Spiral\Http\Request\RequestFilter;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class RequestDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * @var array
     */
    private $mapping = [];

    /**
     * @param string $name
     * @param string $comment
     * @param array  $mapping
     */
    public function __construct(string $name, string $comment = '', array $mapping = [])
    {
        parent::__construct($name, 'RequestFilter', [], $comment);
        $this->mapping = $mapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [RequestFilter::class => null];
    }

    /**
     * Add new field to request and generate default filters and validations if type presented in
     * mapping.
     *
     * @param string $field
     * @param string $type
     * @param string $source
     * @param string $origin
     */
    public function declareField(string $field, string $type, string $source, string $origin = null)
    {
        $schema = $this->constant('SCHEMA')->getValue();
        $setters = $this->constant('SETTERS')->getValue();
        $validates = $this->constant('VALIDATES')->getValue();

        if (!isset($this->mapping[$type])) {
            $schema[$field] = $source . ':' . ($origin ? $origin : $field);

            $this->constant('SCHEMA')->setValue($schema);

            return;
        }

        $definition = $this->mapping[$type];

        //Source can depend on type
        $source = $definition['source'];
        $schema[$field] = $source . ':' . ($origin ? $origin : $field);

        if (!empty($definition['setter'])) {
            //Pre-defined setter
            $setters[$field] = $definition['setter'];
        }

        if (!empty($definition['validates'])) {
            //Pre-defined validation
            $validates[$field] = $definition['validates'];
        }

        $this->constant('SCHEMA')->setValue($schema);
        $this->constant('SETTERS')->setValue($setters);
        $this->constant('VALIDATES')->setValue($validates);
    }

    /**
     * Declare record entity structure.
     */
    protected function declareStructure()
    {
        $this->constant('SCHEMA')->setValue([]);
        $this->constant('SETTERS')->setValue([]);
        $this->constant('VALIDATES')->setValue([]);
    }
}
