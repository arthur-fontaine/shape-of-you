<?php

namespace App\EventListener;

use App\Message\CreateAiFunctionsAndProceduresInDbMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Migrations\Event\MigrationsEventArgs;
use Doctrine\Migrations\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsDoctrineListener(event: Events::onMigrationsMigrated)]
class MigrationEventListener implements EventSubscriberInterface
{
  public function __construct(private MessageBusInterface $bus) {}

  public static function getSubscribedEvents()
  {
    return [
      Events::onMigrationsMigrated => 'onMigrationsMigrated',
    ];
  }

  public function onMigrationsMigrated(
    MigrationsEventArgs $args,
  ) {
    $this->bus->dispatch(new CreateAiFunctionsAndProceduresInDbMessage());
  }
}
