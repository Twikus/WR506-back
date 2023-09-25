<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Movie; // Assurez-vous d'importer la classe de votre entité Movie

class EntitySavedEvent extends Event
{
    private $entity;

    public function __construct(Movie $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity(): Movie
    {
        return $this->entity;
    }
}
