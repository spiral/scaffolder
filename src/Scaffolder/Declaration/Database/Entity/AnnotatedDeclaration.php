<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\Database\Entity;

use Spiral\Reactor\Partial\Property;
use Spiral\Scaffolder\Declaration\Database\AbstractEntityDeclaration;

class AnnotatedDeclaration extends AbstractEntityDeclaration
{
    /**
     * {@inheritDoc}
     */
    public function addField(string $name, string $accessibility, string $type, ?string $as): Property
    {
        $property = parent::addField($name, $accessibility, $type, $as);
        $property->setComment($this->makeFieldComment($name, $type, $as));

        return $property;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return ['Cycle\Annotated\Annotation' => null];
    }

    public function finalize(): void
    {
        $entities = [];
        if ($this->role) {
            $entities[] = "role = \"$this->role\"";
        }
        if ($this->mapper) {
            $entities[] = "mapper = \"$this->mapper\"";
        }
        if ($this->repository) {
            $entities[] = "repository = \"$this->repository\"";
        }
        if ($this->table) {
            $entities[] = "table = \"$this->table\"";
        }
        if ($this->database) {
            $entities[] = "database = \"$this->database\"";
        }

        if (!empty($entities)) {
            $entity = join(', ', $entities);
            $this->setComment("/**\n * @Annotation\Entity($entity)\n */");
        }
    }

    private function makeFieldComment(string $name, string $type, ?string $as): string
    {
        $columns = ["type = \"$type\""];
        if ($as && $as !== $name) {
            $columns[] = "name = \"$as\"";
        }
        $column = join(', ', $columns);

        return "@Annotation\Column($column)";
    }
}