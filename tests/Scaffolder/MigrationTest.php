<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Migrations\SampleMigration;


class MigrationTest extends BaseTest
{
    public function tearDown()
    {
        foreach ($this->files->getFiles(directory('application') . 'migrations') as $file) {
            $this->files->delete($file);
        }
    }

    public function testScaffold()
    {
        $this->console->run('create:migration', [
            'name'     => 'sample',
            '--table'  => 'sample_table',
            '--column' => [
                'id:primary',
                'content:text'
            ]
        ]);

        clearstatcache();

        foreach ($this->files->getFiles(directory('application') . 'migrations') as $file) {
            require_once $file;
        }

        $this->assertTrue(class_exists(SampleMigration::class));

        $reflection = new \ReflectionClass(SampleMigration::class);
        $this->assertTrue($reflection->hasMethod('up'));
        $this->assertTrue($reflection->hasMethod('down'));

        $source = file_get_contents($reflection->getFileName());
        $this->assertContains('sample_table', $source);
        $this->assertContains('id', $source);
        $this->assertContains('primary', $source);
        $this->assertContains('content', $source);
        $this->assertContains('text', $source);
    }
}