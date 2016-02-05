<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Declarations;

use Spiral\Reactor\ClassDeclaration;

class RequestDeclaration extends EntityDeclaration
{
    /**
     * @var array
     */
    private $mapping = [];

    /**
     * Input mapping and default validation rules.
     *
     * @param array $mapping
     */
    public function setMapping(array $mapping)
    {
        $this->mapping = $mapping;
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
    public function declareField($field, $type, $source, $origin = null)
    {
        $schema = $this->property('schema')->getDefault();
        $setters = $this->property('setters')->getDefault();
        $validates = $this->property('validates')->getDefault();

        if (!isset($this->mapping[$type])) {
            $schema[$field] = $source . ':' . ($origin ? $origin : $field);

            $this->declareType($field, $type);
            $this->property('schema')->setDefault($schema);

            return;
        }

        if (!is_array($type)) {
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
        } else {
            $type = $type[0] . '[]';
        }

        $this->declareType($field, !empty($definition['type']) ? $definition['type'] : $type);

        $this->property('schema')->setDefault($schema);
        $this->property('setters')->setDefault($setters);
        $this->property('validates')->setDefault($validates);
    }

    /**
     * Drop non required properties and methods
     *
     * @return $this
     */
    public function normalize()
    {
        $this->properties()->remove('hidden');
        $this->properties()->remove('fillable');

        return $this;
    }

    /**
     * Declare record entity structure.
     */
    protected function declareStructure()
    {
        $this->property('schema')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('schema')->setComment('@var array')->setDefault([]);

        $this->property('setters')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('setters')->setComment('@var array')->setDefault([]);

        parent::declareStructure();
    }

    /**
     * @param string $field
     * @param string $type
     */
    private function declareType($field, $type)
    {
        $this->comment()->addLine("@property-read {$type} \${$field}");
    }
}
