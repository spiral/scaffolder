<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands\Database;

use Spiral\ODM\DocumentEntity;
use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Declarations\Database\DocumentEntityDeclaration;
use Spiral\Scaffolder\Exceptions\ScaffolderException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DocumentEntityCommand extends AbstractCommand
{
    /**
     * Element to be managed.
     */
    const ELEMENT = 'documentEntity';

    /**
     * @var string
     */
    protected $name = 'create:entity';

    /**
     * @var string
     */
    protected $description = 'Create new DocumentEntity model';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Document entity name']
    ];

    /**
     * Create controller declaration.
     */
    public function perform()
    {
        /**
         * @var DocumentEntityDeclaration $declaration
         */
        $declaration = $this->createDeclaration([
            'parent' => DocumentEntity::class
        ]);

        foreach ($this->option('field') as $field) {
            if (strpos($field, ':') === false) {
                throw new ScaffolderException("Field definition must in 'name:type' form.");
            }

            list($name, $type) = explode(':', $field);
            $declaration->declareField($name, $type);
        }

        $this->writeDeclaration($declaration);
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
    {
        return [
            [
                'field',
                'f',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Add field in a format "name:type"'
            ],
            [
                'comment',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Optional comment to add as class header'
            ]
        ];
    }
}