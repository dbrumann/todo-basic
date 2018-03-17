<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;

final class TaskManager
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function changeStatus(Task $task): void
    {
        $task->isDone() ? $task->setOpen() : $task->setDone();

        $this->entityManager->flush();
    }
}
