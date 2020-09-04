<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DoctrineCategorySubscriber implements EventSubscriberInterface
{
    public function onDoctrineEventSubscriber($event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'doctrine.event_subscriber' => 'onDoctrineEventSubscriber',
        ];
    }
}
