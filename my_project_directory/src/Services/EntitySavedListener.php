<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Event\EntitySavedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EntitySavedListener
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // Vérifiez si l'entité est de type Movie
        if ($entity instanceof \App\Entity\Movie) {
            // Déclenchez l'événement personnalisé
            $event = new EntitySavedEvent($entity);
            $this->eventDispatcher->dispatch($event);
        }
    }
}
