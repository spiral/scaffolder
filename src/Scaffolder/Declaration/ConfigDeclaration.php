<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration;

use Spiral\Core\InjectableConfig;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;

class ConfigDeclaration extends ClassDeclaration implements DependedInterface
{
    /** @var FilesInterface */
    private $files;

    /** @var string */
    private $directory;

    /** @var string */
    private $configName;

    /**
     * @param FilesInterface $files
     * @param string         $configName
     * @param string         $name
     * @param string         $comment
     * @param string         $directory
     */
    public function __construct(
        FilesInterface $files,
        string $configName,
        string $name,
        string $comment = '',
        string $directory = ''
    ) {
        parent::__construct($name, 'InjectableConfig', [], $comment);

        $this->files = $files;
        $this->directory = $directory;
        $this->configName = $configName;

        $this->declareStructure();
        $this->createConfigFile();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [InjectableConfig::class => null];
    }

    /**
     * Declare constant and property.
     */
    private function declareStructure(): void
    {
        $this->constant('CONFIG')->setPublic()->setValue($this->configName);
        $this->property('config')->setProtected()->setDefaultValue([]);
    }

    private function createConfigFile(): void
    {
        $configFilename = $this->directory . $this->configName . '.php';
        $this->files->touch($configFilename);

        $lines = [
            '<?php',
            'declare(strict_types=1);',
            '',
            'return [];'
        ];
        $this->files->write($configFilename, join("\n", $lines));
    }
}
