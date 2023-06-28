<?php

namespace App\EventSubscriber;

use App\Entity\Sizable;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class ImageChangedSubscriber implements EventSubscriberInterface
{

    /**
     * @param Sizable $object
     * @return void
     */
    private function setRatio(Sizable $object): void
    {
        if ($object->getRatio() === null) {
            $object->setRatio(round($object->getWidth() / $object->getHeight(), 5));
        }
    }

    /**
     * @param PrePersistEventArgs $args
     * @return void
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        if (! (($object = $args->getObject()) instanceof Sizable)) {
            return;
        }
        $this->setRatio($object);
    }

    /**
     * @param PreUpdateEventArgs $args
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        if (! (($object = $args->getObject()) instanceof Sizable)) {
            return;
        }
        $this->setRatio($object);
    }

    /**
     * @return array|string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }
}
