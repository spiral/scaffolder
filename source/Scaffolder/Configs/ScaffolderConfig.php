<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Scaffolder\Configs;

use Doctrine\Common\Inflector\Inflector;
use Spiral\Core\InjectableConfig;
use Spiral\Scaffolder\Exceptions\ScaffolderException;

/**
 * Configuration for default scaffolder namespaces and other rendering options.
 */
class ScaffolderConfig extends InjectableConfig
{
    /**
     * Associated config section.
     */
    const CONFIG = 'modules/scaffolder';

    /**
     * @var array
     */
    protected $config = [
        'header'       => [],
        'directory'    => '',
        'namespace'    => '',
        'declarations' => [],
    ];

    /**
     * @return array
     */
    public function headerLines(): array
    {
        return $this->config['header'];
    }

    /**
     * @return string
     */
    public function baseDirectory(): string
    {
        return $this->config['directory'];
    }

    /**
     * @return string
     */
    public function baseNamespace(): string
    {
        return trim($this->config['namespace'], '\\');
    }

    /**
     * @param string $element
     * @param string $name
     *
     * @return string
     */
    public function className(string $element, string $name): string
    {
        list($namespace, $name) = $this->parseName($name);

        return Inflector::classify($name) . $this->elementPostfix($element);
    }

    /**
     * @param string $element
     * @param string $name
     *
     * @return string
     */
    public function classNamespace(string $element, string $name = ''): string
    {
        $localNamespace = trim($this->getOption($element, 'namespace', ''), '\\');
        list($namespace, $name) = $this->parseName($name);

        if (!empty($namespace)) {
            $localNamespace .= '\\' . Inflector::classify($namespace);
        }

        if (empty($this->baseNamespace())) {
            return $localNamespace;
        }

        return trim($this->baseNamespace() . '\\' . $localNamespace, '\\');
    }

    /**
     * @param string $element
     * @param string $name
     *
     * @return string
     */
    public function classFilename(string $element, string $name): string
    {
        $namespace = $this->classNamespace($element, $name);
        $directory = $this->baseDirectory() . '/' . str_replace('\\', '/', $namespace);

        return rtrim($directory, '/') . '/' . $this->className($element, $name) . '.php';
    }

    /**
     * @param string $element
     *
     * @return string
     *
     * @throws ScaffolderException
     */
    public function declarationClass(string $element): string
    {
        $class = $this->getOption($element, 'class');

        if (empty($class)) {
            throw new ScaffolderException(
                "Unable to scaffold '{$element}', no declaration class found"
            );
        }

        return $class;
    }

    /**
     * Declaration options.
     *
     * @param string $element
     *
     * @return array
     */
    public function declarationOptions(string $element): array
    {
        return $this->getOption($element, 'options', []);
    }

    /**
     * @param string $element
     *
     * @return string
     */
    private function elementPostfix(string $element): string
    {
        return $this->getOption($element, 'postfix', '');
    }

    /**
     * @param string $element
     * @param string $section
     * @param mixed  $default
     *
     * @return mixed
     */
    private function getOption(string $element, string $section, $default = null)
    {
        if (!isset($this->config['declarations'][$element])) {
            throw new ScaffolderException("Undefined declaration '{$element}'.");
        }

        if (array_key_exists($section, $this->config['declarations'][$element])) {
            return $this->config['declarations'][$element][$section];
        }

        return $default;
    }

    /**
     * Split user name into namespace and class name.
     *
     * @param string $name
     *
     * @return array [namespace, name]
     */
    private function parseName(string $name): array
    {
        $name = str_replace('/', '\\', $name);

        if (strpos($name, '\\') !== false) {
            $names = explode('\\', $name);
            $class = array_pop($names);

            return [join('\\', $names), $class];
        }

        //No user namespace
        return ['', $name];
    }
}