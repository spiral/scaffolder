<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
namespace Spiral;

use Spiral\Core\DirectoriesInterface;
use Spiral\Modules\ModuleInterface;
use Spiral\Modules\PublisherInterface;
use Spiral\Modules\RegistratorInterface;

class ScaffolderModule implements ModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(RegistratorInterface $registrator)
    {
        //To ensure that our commands can be located
        $registrator->configure('tokenizer', 'directories', 'spiral/scaffolder', [
            "directory('libraries') . 'spiral/scaffolder'"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function publish(PublisherInterface $publisher, DirectoriesInterface $directories)
    {
        $publisher->publish(
            __DIR__ . '/config/scaffolder.php',
            $directories->directory('config') . 'modules/scaffolder.php',
            PublisherInterface::FOLLOW
        );
    }
}