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

    /**
     * @throws \Throwable
     */
    public function testConfigFile(): void
    {
        $this->console()->run('create:config', [
            'name'      => 'sample',
            '--comment' => 'Sample Config'
        ]);

        clearstatcache();

        $filename = $this->app->directory('config') . 'sample.php';
        $this->assertFileExists($filename);

        $this->deleteConfigFile($filename);
    }

    private function deleteConfigFile(string $filename): void
    {
        $this->files()->delete($filename);
    }
}