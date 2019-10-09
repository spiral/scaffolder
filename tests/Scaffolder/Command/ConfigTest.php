<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Tests\Scaffolder\Command;

class ConfigTest extends AbstractCommandTest
{
    private const CLASS_NAME = '\\TestApp\\Config\\SampleConfig';

    public function tearDown(): void
    {
        $this->deleteDeclaration(self::CLASS_NAME);
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function testScaffold(): void
    {
        $this->console()->run('create:config', [
            'name'      => 'sample',
            '--comment' => 'Sample Config'
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(self::CLASS_NAME));

        $reflection = new \ReflectionClass(self::CLASS_NAME);

        $this->assertStringContainsString('Sample Config', $reflection->getDocComment());

        $this->assertTrue($reflection->hasConstant('CONFIG'));
        $this->assertTrue($reflection->hasProperty('config'));

        $this->assertIsString($reflection->getReflectionConstant('CONFIG')->getValue());
        $this->assertEquals([], $reflection->getDefaultProperties()['config']);
    }

    public function testReverse(): void
    {
        $className = '\\TestApp\\Config\\ReversedConfig';
        $this->console()->run('create:config', [
            'name'      => 'reversed',
            '--comment' => 'Reversed Config',
            '--reverse' => true
        ]);

        clearstatcache();
        $this->assertTrue(class_exists($className));

        $reflection = new \ReflectionClass($className);

        $this->assertTrue($reflection->hasConstant('CONFIG'));
        $this->assertTrue($reflection->hasProperty('config'));

        $this->assertIsString($reflection->getReflectionConstant('CONFIG')->getValue());
        $this->assertIsArray($reflection->getDefaultProperties()['config']);
        $this->assertNotEmpty($reflection->getDefaultProperties()['config']);

        $methods = [
            'getStrParam'   => ['hint' => 'string', 'annotation' => 'string'],
            'getIntParam'   => ['hint' => 'int', 'annotation' => 'int'],
            'getFloatParam' => ['hint' => 'float', 'annotation' => 'float'],
            'getBoolParam'  => ['hint' => 'bool', 'annotation' => 'bool'],
            'getNullParam'  => ['hint' => null, 'annotation' => 'null'],

            'getArrParam'   => ['hint' => 'array', 'annotation' => 'array|string[]'],
            'getArrParamBy' => ['hint' => 'string', 'annotation' => 'string'],

            'getMapParam'   => ['hint' => 'array', 'annotation' => 'array|string[]'],
            'getMapParamBy' => ['hint' => 'string', 'annotation' => 'string'],

            'getMixedArrParam' => ['hint' => 'array', 'annotation' => 'array'],
            'getParams'        => ['hint' => 'array', 'annotation' => 'array|string[]'],

            'getParameters' => ['hint' => 'array', 'annotation' => 'array|array[]'],
            'getParameter'  => ['hint' => 'array', 'annotation' => 'array'],

            'getConflicts'  => ['hint' => 'array', 'annotation' => 'array|array[]'],
            'getConflict'   => ['hint' => 'string', 'annotation' => 'string'],
            'getConflictBy' => ['hint' => 'array', 'annotation' => 'array|int[]'],

            'getValues'  => ['hint' => 'array', 'annotation' => 'array|array[]'],
            'getValue'   => ['hint' => 'string', 'annotation' => 'string'],
            'getValueBy' => ['hint' => 'string', 'annotation' => 'string'],
        ];

        $reflectionMethods = [];
        foreach ($reflection->getMethods() as $method) {
            if ($method->getDeclaringClass()->name === $reflection->name) {
                $reflectionMethods[$method->name] = $method;
                $this->assertArrayHasKey($method->name, $methods);

                if (!$method->hasReturnType()) {
                    $this->assertNull($methods[$method->name]['hint']);
                } else {
                    $this->assertEquals(
                        $methods[$method->name]['hint'],
                        $method->getReturnType()->getName(),
                        "Method '{$method->name}' has '{$method->getReturnType()->getName()}' return type instead of expecting '{$methods[$method->name]['hint']}'"
                    );
                }

                $this->assertStringContainsString(
                    $methods[$method->name]['annotation'],
                    $method->getDocComment(),
                    "Method '{$method->name}' has '{$method->getDocComment()}' annotation and doesn't contain expected '{$methods[$method->name]['annotation']}'"
                );
            }
        }

        $this->assertCount(count($methods), $reflectionMethods);

        $this->deleteDeclaration($className);
    }

    /**
     * @throws \Throwable
     */
    public function testConfigFile(): void
    {
        $filename = $this->createConfig('sample', 'Sample Config');

        $this->deleteConfigFile($filename);
    }

    /**
     * @throws \Throwable
     */
    public function testConfigFileExists(): void
    {
        $filename = $this->createConfig('sample2', 'Sample2 Config');
        $this->files()->append($filename, '//sample comment');

        $source = $this->files()->read($filename);
        $this->assertStringContainsString('//sample comment', $source);

        $filename = $this->createConfig('sample2', 'Sample2 Config');

        $source = $this->files()->read($filename);
        $this->assertStringContainsString('//sample comment', $source);

        $this->deleteConfigFile($filename);
        $this->deleteDeclaration('\\TestApp\\Config\\Sample2Config');
    }

    /**
     * @param string $filename
     */
    private function deleteConfigFile(string $filename): void
    {
        $this->files()->delete($filename);
    }

    /**
     * @param string $name
     * @param string $comment
     * @return string
     * @throws \Throwable
     */
    private function createConfig(string $name, string $comment): string
    {
        $this->console()->run('create:config', [
            'name'      => $name,
            '--comment' => $comment
        ]);

        clearstatcache();

        $filename = $this->app->directory('config') . "$name.php";
        $this->assertFileExists($filename);

        return $filename;
    }
}
