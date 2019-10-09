<?php

/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Declaration;

use Doctrine\Common\Inflector\Inflector;
use Spiral\Core\InjectableConfig;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\DependedInterface;
use Spiral\Reactor\Partial\Method;
use Spiral\Scaffolder\Exception\ScaffolderException;

class ConfigDeclaration extends ClassDeclaration implements DependedInterface
{
    /** @var FilesInterface */
    private $files;

    /** @var string */
    private $directory;

    /** @var string */
    private $configName;

    /** @var ConfigDeclaration\ReturnTypes */
    private $returnTypes;

    /** @var ConfigDeclaration\DefaultValues */
    private $defaultValues;

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
        $this->returnTypes = new ConfigDeclaration\ReturnTypes();
        $this->defaultValues = new ConfigDeclaration\DefaultValues();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [InjectableConfig::class => null];
    }

    /**
     * @param bool $reverse
     */
    public function create(bool $reverse): void
    {
        $filename = $this->makeConfigFilename($this->configName);
        if ($reverse) {
            if (!$this->files->exists($filename)) {
                throw new ScaffolderException("Config filename $filename doesn't exist");
            }

            $defaultsFromFile = require $filename;
            $this->declareGetters($defaultsFromFile);
            $this->declareStructure($this->configName, $this->defaultValues->get($defaultsFromFile));
        } else {
            if (!$this->files->exists($filename)) {
                $this->touchConfigFile($filename);
            }

            $this->declareStructure($this->configName, []);
        }
    }

    /**
     * @param string $filename
     * @return string
     */
    private function makeConfigFilename(string $filename): string
    {
        return "{$this->directory}{$filename}.php";
    }

    /**
     * @param string $filename
     */
    private function touchConfigFile(string $filename): void
    {
        $this->files->touch($filename);

        $lines = [
            '<?php',
            'declare(strict_types=1);',
            '',
            'return [];'
        ];
        $this->files->write($filename, join("\n", $lines));
    }

    /**
     * @param array $defaults
     * @return double[]|float[]
     */
    private function declareGetters(array $defaults): array
    {
        $output = [];
        $getters = [];
        $gettersByKey = [];

        foreach ($defaults as $key => $value) {
            $key = (string)$key;
            $getter = $this->makeGetterName($key);
            $getters[] = $getter;

            $method = $this->method($getter)->setPublic();
            $method->setSource("return \$this->config['$key'];");
            $method->setComment("@return {$this->returnTypes->getAnnotation($value)}");

            if (is_array($value)) {
                $gettersByKey[] = compact('key', 'value');
            }

            $returnTypeHint = $this->returnTypes->getHint(gettype($value));
            if ($returnTypeHint !== null) {
                $method->setReturn($returnTypeHint);
            }
        }

        foreach ($gettersByKey as $item) {
            $method = $this->declareGettersByKey($getters, $item['key'], $item['value']);
            if ($method !== null) {
                $getters[] = $method->getName();
            }
        }

        return $output;
    }

    /**
     * @param array  $methodNames
     * @param string $key
     * @param array  $value
     * @return Method|null
     */
    private function declareGettersByKey(array $methodNames, string $key, array $value): ?Method
    {
        //Wont create if there's less than 2 sub-items
        if (count($value) < 2) {
            return null;
        }

        $singularKey = Inflector::singularize($key);
        $name = $this->makeGetterName($singularKey);
        if (in_array($name, $methodNames, true)) {
            $name = $this->makeGetterName($singularKey, 'get', 'by');
        }

        //Name conflict, wont merge
        if (in_array($name, $methodNames, true)) {
            return null;
        }

        ['keyType' => $keyType, 'valueType' => $valueType] = $this->defineReturnTypes($value);
        //We need a fixed structure here
        if ($keyType === null || $valueType === null) {
            return null;
        }

        $method = $this->method($name)->setPublic();
        $method->parameter($singularKey)->setType($keyType);
        $method->setSource("return \$this->config['$key'][\$$singularKey];");
        $method->setReturn($valueType);
        $method->setComment([
            "@param $keyType $singularKey",
            "@return {$this->returnTypes->getAnnotation(array_values($value)[0])}"
        ]);

        return $method;
    }

    /**
     * @param string $name
     * @param string $prefix
     * @param string $postfix
     * @return string
     */
    private function makeGetterName(string $name, string $prefix = 'get', string $postfix = ''): string
    {
        $chunks = [];
        if (!empty($prefix)) {
            $chunks[] = $prefix;
        }

        $chunks[] = count($chunks) !== 0 ? ucfirst($name) : $name;
        if (!empty($postfix)) {
            $chunks[] = ucfirst($postfix);
        }

        return join('', $chunks);
    }

    /**
     * @param array $value
     * @return array
     */
    private function defineReturnTypes(array $value): array
    {
        $keys = [];
        $values = [];
        foreach ($value as $k => $v) {
            $keys[] = gettype($k);
            $values[] = gettype($v);
        }

        $keys = array_unique($keys);
        $values = array_unique($values);

        return [
            'keyType'   => count($keys) === 1 ? $keys[0] : null,
            'valueType' => count($values) === 1 ? $values[0] : null,
        ];
    }

    /**
     * Declare constant and property.
     *
     * @param string $configName
     * @param array  $defaults
     */
    private function declareStructure(string $configName, array $defaults): void
    {
        $this->constant('CONFIG')->setPublic()->setValue($configName);
        $this->property('config')->setProtected()->setDefaultValue($defaults);
    }
}
