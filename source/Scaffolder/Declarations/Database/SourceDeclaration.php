<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Scaffolder\Declarations\Database;

use Spiral\ODM\Entities\DocumentSource;
use Spiral\ORM\Entities\RecordSource;
use Spiral\Reactor\Body\Source;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;
use Spiral\Scaffolder\Exceptions\ScaffolderException;

class SourceDeclaration extends ClassDeclaration implements DependedInterface
{
    const TYPES = [
        'record'   => [
            'extends'  => RecordSource::class,
            'constant' => 'RECORD'
        ],
        'document' => [
            'extends'  => DocumentSource::class,
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

        parent::__construct(
            $name,
            $this->fetchName(self::TYPES[$type]['extends']),
            [],
            $comment
        );

        $this->createDeclaration();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            self::TYPES[$this->type]['extends'] => null,
            $this->model                        => null,
        ];
    }

    /**
     * Create source declaration.
     */
    private function createDeclaration()
    {
        $this->constant(self::TYPES[$this->type]['constant'])->setValue(
            new Source([$this->fetchName($this->model) . '::class'])
        );
    }

    /**
     * Fetch short class name.
     *
     * @param string $class
     *
     * @return string
     */
    private function fetchName(string $class): string
    {
        $reflection = new \ReflectionClass($class);

        return $reflection->getShortName();
    }
}