<?php

namespace App\EventSubscriber\Doctrine;

use App\Entity\Link\LinkInterface;
use App\Persistence\Generator\LinkGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class LinkPrePersistEventListener implements EventSubscriber
{
    public function __construct(
        private LinkGenerator $generator,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof LinkInterface) {
           return;
        }

        $identifier = $this->generator->generate($args->getObjectManager(), $entity);
        $entity->shortenedUri($identifier);
    }

    public function getSubscribedEvents(): array
    {
        return ['prePersist'];
    }
}