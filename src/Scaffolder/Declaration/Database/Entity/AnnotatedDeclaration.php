<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration\Database\Entity;

use Doctrine\Common\Inflector\Inflector;
use Spiral\Reactor\Partial\Property;
use Spiral\Scaffolder\Declaration\Database\AbstractEntityDeclaration;

class AnnotatedDeclaration extends AbstractEntityDeclaration
{
    /**
     * {@inheritDoc}
     */
    public function addField(string $name, string $accessibility, string $type): Property
    {
        $property = parent::addField($name, $accessibility, $type);
        $property->setComment($this->makeFieldComment($name, $type));

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
        if (!empty($this->role)) {
            $entities[] = "role = \"$this->role\"";
        }
        if (!empty($this->mapper)) {
            $entities[] = "mapper = \"$this->mapper\"";
        }
        if (!empty($this->repository)) {
            $entities[] = "repository = \"$this->repository\"";
        }
        if (!empty($this->table)) {
            $entities[] = "table = \"$this->table\"";
        }
        if (!empty($this->database)) {
            $entities[] = "database = \"$this->database\"";
        }

        if (!empty($entities)) {
            $entity = join(', ', $entities);
            $this->setComment("@Annotation\Entity($entity)");
        }
    }

    private function makeFieldComment(string $name, string $type): string
    {
        $columns = ["type = \"$type\""];

        if (!empty($this->inflection)) {
            $inflected = $this->inflect($this->inflection, $name);
            if ($inflected !== null && $inflected !== $name) {
                $columns[] = "name = \"$inflected\"";
            }
        }

        $column = join(', ', $columns);

        return "@Annotation\Column($column)";
    }

    private function inflect(string $inflection, string $value): ?string
    {
        switch ($inflection) {
            case 'tableize':
                return Inflector::tableize($value);

            case 'camelize':
                return Inflector::camelize($value);

            default:
                throw new \UnexpectedValueException("Unknown inflection, got `$inflection`");
        }
    }
}