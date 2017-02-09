<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Declarations\Database;

use Spiral\ORM\Record;
use Spiral\Scaffolder\Declarations\EntityDeclaration;

class RecordDeclaration extends EntityDeclaration
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct(string $name, string $comment = '')
    {
        parent::__construct($name, Record::class, $comment);
    }

    /**
     * @param string $table
     */
    public function setTable(string $table)
    {
        $this->constant('TABLE')->setValue($table);
    }

    /**
     * @return string|null
     */
    public function getTable()
    {
        return $this->constant('TABLE')->getValue();
    }

    /**
     * @param string $database
     */
    public function setDatabase(string $database)
    {
        $this->constant('DATABASE')->setValue($database);
    }

    /**
     * @return string|null
     */
    public function getDatabase()
    {
        return $this->constant('DATABASE')->getValue();
    }

    /**
     * Drop non required properties and methods
     *
     * @return $this
     */
    public function normalizeDeclaration()
    {
        if (empty($this->getTable())) {
            $this->getConstants()->remove('TABLE');
        }

        if (empty($this->getDatabase())) {
            $this->getConstants()->remove('DATABASE');
        }

        return $this;
    }

    /**
     * Declare record entity structure.
     */
    protected function declareStructure()
    {
        $this->constant('TABLE');
        $this->constant('DATABASE');

        parent::declareStructure();

        $this->constant('DEFAULTS')->setValue([]);
        $this->constant('INDEXES')->setValue([]);
    }
}