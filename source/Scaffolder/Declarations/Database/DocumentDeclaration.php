<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Declarations\Database;

use Spiral\Reactor\ClassDeclaration;

class DocumentDeclaration extends DocumentEntityDeclaration
{
    /**
     * @param string $table
     * @return $this
     */
    public function setCollection($table)
    {
        return $this->property('collection')->setDefault($table);
    }

    /**
     * @return string
     */
    public function getCollection()
    {
        return $this->property('collection')->getDefault();
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
     * Declare record entity structure.
     */
    protected function declareStructure()
    {
        $this->property('table')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('table')->setComment('@var string');

        $this->property('database')->setAccess(ClassDeclaration\PropertyDeclaration::ACCESS_PROTECTED);
        $this->property('database')->setComment('@var string');

        parent::declareStructure();
    }
}