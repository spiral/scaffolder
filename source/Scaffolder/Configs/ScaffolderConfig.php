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
        'header'    => [],
        'directory' => '',
        'namespace' => '',
        'elements'  => []
    ];

    /**
     * @return array
     */
    public function headerLines()
    {
        return $this->config['header'];
    }

    /**
     * @return string
     */
    public function baseDirectory()
    {
        return $this->config['directory'];
    }

    /**
     * @return string
     */
    public function baseNamespace()
    {
        return trim($this->config['namespace'], '\\');
    }

    /**
     * @param string $element
     * @param string $name
     * @return string
     */
    public function elementName($element, $name)
    {
        list($namespace, $name) = $this->splitName($name);

        return Inflector::classify($name) . $this->elementPostfix($element);
    }

    /**
     * @param string $element
     * @param string $name
     * @return string
     */
    public function elementNamespace($element, $name = '')
    {
        $localNamespace = trim($this->elementOption($element, 'namespace', ''), '\\');
        list($namespace, $name) = $this->splitName($name);

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
     * @return string
     */
    public function elementFilename($element, $name)
    {
        $namespace = $this->elementNamespace($element, $name);
        $directory = $this->baseDirectory() . '/' . str_replace('\\', '/', $namespace);

        return rtrim($directory, '/') . '/' . $this->elementName($element, $name) . '.php';
    }

    /**
     * @param string $element
     * @return string
     * $@throws ScaffolderException
     */
    public function declarationClass($element)
    {
        $class = $this->elementOption($element, 'class');

        if (empty($class)) {
            throw new ScaffolderException(
                "Unable to scaffold '{$element}', no declaration class found"
            );
        }

        return $class;
    }

    /**
     * @param string $element
     * @return string
     */
    private function elementPostfix($element)
    {
        return $this->elementOption($element, 'postfix', '');
    }

    /**
     * @param string $element
     * @param string $section
     * @param null   $default
     * @return mixed
     */
    private function elementOption($element, $section, $default = null)
    {
        if (!isset($this->config['elements'][$element])) {
            throw new ScaffolderException("Undefined element '{$element}'.");
        }

        if (array_key_exists($section, $this->config['elements'][$element])) {
            return $this->config['elements'][$element][$section];
        }

        return $default;
    }

    /**
     * Split user name into namespace and class name.
     *
     * @param string $name
     * @return array [namespace, name]
     */
    private function splitName($name)
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