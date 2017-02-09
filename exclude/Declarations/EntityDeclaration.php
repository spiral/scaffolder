<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Declarations;

use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

/**
 * Common parent implementation for requests, records and documents.
 */
abstract class EntityDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * Parent class
     *
     * @var string
     */
    private $parent = '';

    /**
     * @param string $name
     * @param array  $parent
     * @param string $comment
     */
    public function __construct($name, $parent, $comment = '')
    {
        $reflection = new \ReflectionClass($parent);
        $this->parent = $reflection->getName();

        parent::__construct($name, $reflection->getShortName(), [], $comment);
        $this->declareStructure();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            $this->parent => null
        ];
    }

    /**
     * Make field fillable (mass assigment)
     *
     * @param string $field
     *
     * @return $this
     */
    public function openField($field)
    {
        $fillable = $this->property('fillable')->getDefault();
        $fillable[] = $field;
        $this->property('fillable')->setDefault($fillable);

        return $this;
    }

    /**
     * Hide field from publicFields method and json serialization
     *
     * @param string $field
     *
     * @return $this
     */
    public function hideField($field)
    {
        $hidden = $this->property('hidden')->getDefault();
        $hidden[] = $field;
        $this->property('hidden')->setDefault($hidden);

        return $this;
    }

    /**
     * Set validation rules for a given field
     *
     * @param string $field
     * @param array  $rules
     *
     * @return $this
     */
    public function validateField($field, array $rules)
    {
        $validates = $this->property('validates')->getDefault();
        $validates[$field] = $rules;
        $this->property('validates')->setDefault($validates);

        return $this;
    }

    /**
     * Declare entity structure.
     */
    protected function declareStructure()
    {
        $this->property('fillable')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('fillable')->setComment('@var array')->setDefault([]);

        $this->property('hidden')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('hidden')->setComment('@var array')->setDefault([]);

        $this->property('validates')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('validates')->setComment('@var array')->setDefault([]);
    }
}