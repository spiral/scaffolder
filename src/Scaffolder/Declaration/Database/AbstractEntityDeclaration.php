<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\Database;

use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;
use Spiral\Reactor\Partial\Property;

abstract class AbstractEntityDeclaration extends ClassDeclaration implements DependedInterface
{
    /** @var string|null */
    protected $role;

    /** @var string|null */
    protected $mapper;

    /** @var string|null */
    protected $repository;

    /** @var string|null */
    protected $table;

    /** @var string|null */
    protected $database;

    /**
     * @param string|null $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @param string|null $mapper
     */
    public function setMapper(string $mapper): void
    {
        $this->mapper = $mapper;
    }

    /**
     * @param string $repository
     */
    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @param string|null $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function setDatabase(string $database): void
    {
        $this->database = $database;
    }

    /**
     * Add field.
     *
     * @param string      $name
     * @param string      $accessibility
     * @param string      $type
     * @param string|null $as
     * @return Property
     */
    public function addField(string $name, string $accessibility, string $type, ?string $as): Property
    {
        $property = $this->property($name);
        if ($accessibility) {
            $property->setAccess($accessibility);
        }

        return $property;
    }

    abstract public function finalize(): void;

    public static function factory(string $type): self
    {

    }
}