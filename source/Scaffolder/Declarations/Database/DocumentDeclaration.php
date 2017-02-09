<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Declarations\Database;

use Spiral\ODM\Document;
use Spiral\Scaffolder\Declarations\EntityDeclaration;

class DocumentDeclaration extends EntityDeclaration
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct(string $name, string $comment = '')
    {
        parent::__construct($name, Document::class, $comment);
    }

    /**
     * @param string $collection
     */
    public function setCollection(string $collection)
    {
        $this->constant('COLLECTION')->setValue($collection);
    }

    /**
     * @return string|null
     */
    public function getCollection()
    {
        return $this->constant('COLLECTION')->getValue();
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
        if (empty($this->getCollection())) {
            $this->getConstants()->remove('COLLECTION');
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
        $this->constant('COLLECTION');
        $this->constant('DATABASE');

        parent::declareStructure();

        $this->constant('DEFAULTS')->setValue([]);
        $this->constant('INDEXES')->setValue([]);
    }
}