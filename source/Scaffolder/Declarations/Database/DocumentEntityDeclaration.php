<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Declarations\Database;

use Spiral\Reactor\ClassDeclaration;
use Spiral\Scaffolder\Declarations\EntityDeclaration;

class DocumentEntityDeclaration extends EntityDeclaration
{
    /**
     * Add new field to entity schema
     *
     * @param string $field
     * @param string $type
     * @return $this
     */
    public function declareField($field, $type)
    {
        $schema = $this->property('schema')->getDefault();
        $schema[$field] = $type;
        $this->property('schema')->setDefault($schema);

        return $this;
    }


    /**
     * Declare record entity structure.
     */
    protected function declareStructure()
    {
        $this->property('schema')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('schema')->setComment('@var array')->setDefault([]);

        $this->property('defaults')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('defaults')->setComment('@var array')->setDefault([]);

        $this->property('indexes')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('indexes')->setComment('@var array')->setDefault([]);

        parent::declareStructure();
    }
}