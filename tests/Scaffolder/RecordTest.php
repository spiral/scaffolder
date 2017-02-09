<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Database\SampleRecord;

class RecordTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(SampleRecord::class);
    }

    public function testScaffold()
    {
        $this->console->run('create:record', [
            'name'       => 'sample-record',
            '--table'    => 'table',
            '--database' => 'database',
            '--field'    => [
                'id:primary',
                'value:int'
            ]
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleRecord::class));

        $reflection = new \ReflectionClass(SampleRecord::class);

        $this->assertSame([
            'id'    => 'primary',
            'value' => 'int'
        ], $reflection->getConstant('SCHEMA'));

        $this->assertSame('table', $reflection->getConstant('TABLE'));
        $this->assertSame('database', $reflection->getConstant('DATABASE'));
    }
}