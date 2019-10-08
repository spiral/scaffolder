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
use Spiral\Reactor\Traits\CommentTrait;
use Spiral\Scaffolder\Declaration\Database\AbstractEntityDeclaration;

class AnnotatedDeclaration extends AbstractEntityDeclaration
{
    /**
     * {@inheritDoc}
     */
    public function addField(string $name, string $accessibility, string $type): Property
    {
        $property = parent::addField($name, $accessibility, $type);
        $this->addCommentLine($property, $this->makeFieldComment($name, $type));

        return $property;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return ['Cycle\Annotated\Annotation' => 'Cycle'];
    }

    public function declareSchema(): void
    {
        $entities = [];
        $attributes = ['role', 'mapper', 'repository', 'table', 'database'];
        foreach ($attributes as $attribute) {
            if (!empty($this->$attribute)) {
                $entities[] = "$attribute = \"{$this->$attribute}\"";
            }
        }

        $entity = join(', ', $entities);
        $this->addCommentLine($this, "@Cycle\Entity($entity)");
    }

    /**
     * @param CommentTrait $target
     * @param string|array $comment
     */
    protected function addCommentLine($target, $comment): void
    {
        $comments = [];
        if (is_array($comment)) {
            $comments = $comment;
        } elseif (is_string($comment)) {
            $comments = [$comment];
        }

        $target->setComment(array_merge($this->getComment()->getLines(), $comments));
    }

    /**
     * @param string $name
     * @param string $type
     * @return string
     */
    private function makeFieldComment(string $name, string $type): string
    {
        $columns = [];
        if ($this->isNullableType($type)) {
            $columns = ['nullable = true'];
        }
        $columns[] = "type = \"{$this->annotatedType($type)}\"";

        if (!empty($this->inflection)) {
            $columns = $this->addInflectedName($columns, $name);
        }

        $column = join(', ', $columns);

        return "@Cycle\Column($column)";
    }

    /**
     * @param string $inflection
     * @param string $value
     * @return string|null
     */
    private function inflect(string $inflection, string $value): ?string
    {
        switch ($inflection) {
            case 'tableize':
            case 't':
                return Inflector::tableize($value);

            case 'camelize':
            case 'c':
                return Inflector::camelize($value);

            default:
                throw new \UnexpectedValueException("Unknown inflection, got `$inflection`");
        }
    }

    /**
     * @param string $type
     * @return string
     */
    private function annotatedType(string $type): string
    {
        return $this->isNullableType($type) ? substr($type, 1) : $type;
    }

    /**
     * @param array  $columns
     * @param string $name
     * @return array
     */
    private function addInflectedName(array $columns, string $name): array
    {
        $inflected = $this->inflect($this->inflection, $name);
        if ($inflected !== null && $inflected !== $name) {
            $columns[] = "name = \"$inflected\"";
        }

        return $columns;
    }
}
