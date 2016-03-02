<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Declarations;

use Spiral\Migrations\Migration;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\ClassDeclaration\MethodDeclaration;
use Spiral\Reactor\DependedInterface;

/**
 * Migration declaration
 */
class MigrationDeclaration extends ClassDeclaration implements DependedInterface
{
    /**
     * @param string $name
     * @param string $comment
     */
    public function __construct($name, $comment = '')
    {
        parent::__construct($name, 'Migration', [], $comment);

        $this->declareStructure();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            Migration::class => null
        ];
    }

    /**
     * Declare table creation with specific set of columns
     *
     * @param string $table
     * @param array  $columns
     */
    public function declareCreation($table, array $columns)
    {
        $source = $this->method('up')->source();

        $source->addLine("\$this->schema('{$table}')");
        foreach ($columns as $name => $type) {
            $source->addLine("    ->addColumn('{$name}', '{$type}')");
        }

        $source->addLine("    ->create();");

        $this->method('down')->source()->addString("\$this->schema('{$table}')->drop();");
    }

    /**
     * Declare default __invoke method body.
     */
    private function declareStructure()
    {
        $up = $this->method('up')->setAccess(MethodDeclaration::ACCESS_PUBLIC);
        $down = $this->method('down')->setAccess(MethodDeclaration::ACCESS_PUBLIC);

        $up->setComment('Create tables, add columns or insert data here');
        $down->setComment('Drop created, columns and etc here');
    }
}