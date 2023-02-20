<?php

declare(strict_types=1);

namespace App\Doctrine\Messenger;

use App\Doctrine\ReloadableEntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

final class ClearEntityManagerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly ReloadableEntityManagerInterface $em
    ) {
    }

    /**
     * @return mixed[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageHandledEvent::class => 'onWorkerMessageHandled',
            WorkerMessageFailedEvent::class => 'onWorkerMessageFailed',
        ];
    }

    public function onWorkerMessageHandled(): void
    {
        $this->clearEntityManagers();
    }

    public function onWorkerMessageFailed(): void
    {
        $this->clearEntityManagers();
    }

    private function clearEntityManagers(): void
    {
        $this->em->clear();
        gc_collect_cycles();
    }
}
