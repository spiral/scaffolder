<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Scaffolder\Declarations\Database;

use Spiral\ODM\Entities\DocumentSource;
use Spiral\ORM\Entities\RecordSource;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;
use Spiral\Scaffolder\Exceptions\ScaffolderException;

class SourceDeclaration extends ClassDeclaration implements DependedInterface
{
    const TYPES = [
        'record'   => [
            'RecordSource' => RecordSource::class,
            'constant'     => 'RECORD'
        ],
        'document' => [
            'source'   => DocumentSource::class,
            'constant' => 'DOCUMENT'
        ]
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $model;

    /**
     * @param string $name
     * @param string $type
     * @param string $model
     * @param string $comment
     */
    public function __construct(string $name, string $type, string $model, string $comment = '')
    {
        if (!isset(self::TYPES[$type])) {
            throw new ScaffolderException("Undefined source type {$type}");
        }

        $this->type = $type;
        $this->model = $model;

        parent::__construct($name, self::TYPES[$type]['extends'], [], $comment);

        $this->createDeclaration();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            self::TYPES[$this->type]['source'] => null,
            $this->model                       => null,
        ];
    }

    /**
     * Create source declaration.
     */
    private function createDeclaration()
    {
        $this->constant(self::TYPES[$this->type]['constant'])->setValue($this->model);
    }
}