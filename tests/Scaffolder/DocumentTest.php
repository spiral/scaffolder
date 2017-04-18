<?php
/**
 * scaffolder
 *
 * @author    Wolfy-J
 */

namespace Spiral\Tests\Scaffolder;

use Spiral\Tests\BaseTest;
use TestApplication\Database\SampleDocument;
use TestApplication\Database\Sources\SampleDocumentSource;

class DocumentTest extends BaseTest
{
    public function tearDown()
    {
        $this->deleteDeclaration(SampleDocument::class);
        $this->deleteDeclaration(SampleDocumentSource::class);
    }

    public function testScaffold()
    {
        $this->console->run('create:document', [
            'name'         => 'sample-document',
            '--collection' => 'collection',
            '--database'   => 'database',
            '--field'      => [
                'name:string',
                'value:int'
            ],
            '-s'           => true
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(SampleDocument::class));

        $reflection = new \ReflectionClass(SampleDocument::class);

        $this->assertSame([
            'name'  => 'string',
            'value' => 'int'
        ], $reflection->getConstant('SCHEMA'));

        $this->assertSame('collection', $reflection->getConstant('COLLECTION'));
        $this->assertSame('database', $reflection->getConstant('DATABASE'));
    }
}