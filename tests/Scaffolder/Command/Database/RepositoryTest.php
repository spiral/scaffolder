<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Tests\Scaffolder\Command\Database;

use Spiral\Tests\Scaffolder\Command\AbstractCommandTest;

class RepositoryTest extends AbstractCommandTest
{
    private const CLASS_NAME = '\\TestApp\\Repository\\AnotherSampleRepository';

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
        $this->console()->run('create:repository', [
            'name'      => 'anotherSample',
            '--comment' => 'Sample Repository'
        ]);

        clearstatcache();
        $this->assertTrue(class_exists(self::CLASS_NAME));

        $reflection = new \ReflectionClass(self::CLASS_NAME);

        $this->assertStringContainsString('Sample Repository', $reflection->getDocComment());
    }
}