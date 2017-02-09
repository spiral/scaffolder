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

class RecordDeclaration extends EntityDeclaration
{
    /**
     * @param string $table
     * @return $this
     */
    public function setTable($table)
    {
        return $this->property('table')->setDefault($table);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->property('table')->getDefault();
    }

    /**
     * @param string $database
     * @return $this
     */
    public function setDatabase($database)
    {
        return $this->property('database')->setDefault($database);
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->property('database')->getDefault();
    }

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
     * Drop non required properties and methods
     *
     * @return $this
     */
    public function normalize()
    {
        if (empty($this->getTable())) {
            $this->properties()->remove('table');
        }

        if (empty($this->getDatabase())) {
            $this->properties()->remove('database');
        }

        return $this;
    }

    /**
     * Declare record entity structure.
     */
    protected function declareStructure()
    {
        $this->property('table')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('table')->setComment('@var string');

        $this->property('database')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('database')->setComment('@var string');

        $this->property('schema')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('schema')->setComment('@var array')->setDefault([]);

        $this->property('defaults')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('defaults')->setComment('@var array')->setDefault([]);

        $this->property('indexes')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('indexes')->setComment('@var array')->setDefault([]);

        parent::declareStructure();
    }
}