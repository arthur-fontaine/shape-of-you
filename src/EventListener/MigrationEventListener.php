<?php
namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Migrations\Event\MigrationsEventArgs;
use Doctrine\Migrations\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

#[AsDoctrineListener(event: Events::onMigrationsMigrated)]
class MigrationEventListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::onMigrationsMigrated => 'onMigrationsMigrated',
        ];
    }

    public function onMigrationsMigrated(
      MigrationsEventArgs $args,
    )
    {
        $directory = __DIR__ . '/../../database';
        $files = glob($directory .'/**/*.sql');

        $connection = $args->getConnection();

        foreach ($files as $file) {
          $sql = file_get_contents($file);
          $connection->executeStatement($sql);
        }
    }
}
