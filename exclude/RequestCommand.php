<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral\Scaffolder\Commands;

use Spiral\Http\Request\RequestFilter;
use Spiral\Scaffolder\AbstractCommand;
use Spiral\Scaffolder\Configs\ScaffolderConfig;
use Spiral\Scaffolder\Declarations\RequestDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RequestCommand extends AbstractCommand
{
    /**
     * Default input source.
     */
    const DEFAULT_SOURCE = 'data';

    /**
     * Default type to apply.
     */
    const DEFAULT_TYPE = 'string';

    /**
     * Element to be managed.
     */
    const ELEMENT = 'request';

    /**
     * @var string
     */
    protected $name = 'create:request';

    /**
     * @var string
     */
    protected $description = 'Create new RequestFilter model';

    /**
     * @var array
     */
    protected $arguments = [
        ['name', InputArgument::REQUIRED, 'Request name']
    ];

    /**
     * @param ScaffolderConfig $config
     */
    public function perform(ScaffolderConfig $config)
    {
        /**
         * @var RequestDeclaration $declaration
         */
        $declaration = $this->createDeclaration([
            'parent' => RequestFilter::class
        ]);

        $declaration->setMapping($config->getMapping(static::ELEMENT));

        foreach ($this->option('field') as $field) {
            list($field, $type, $source, $origin) = $this->parseField($field);
            $declaration->declareField($field, $type, $source, $origin);
        }

        $this->writeDeclaration($declaration->normalize());
    }

    /**
     * Parse field to fetch source, origin and type.
     *
     * @param string $field
     * @return array
     */
    private function parseField($field)
    {
        $source = static::DEFAULT_SOURCE;
        $type = static::DEFAULT_TYPE;
        $origin = null;

        if (strpos($field, '(') !== false) {
            $source = substr($field, strpos($field, '(') + 1, -1);
            $field = substr($field, 0, strpos($field, '('));

            if (strpos($source, ':') !== false) {
                list($source, $origin) = explode(':', $source);
            }
        }

        if (strpos($field, ':') !== false) {
            list($field, $type) = explode(':', $field);
        }

        return [$field, $type, $source, $origin];
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
                'Input field in a format "field:type(source:origin)" or "field(source)".'
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