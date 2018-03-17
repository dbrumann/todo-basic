<?php declare(strict_types = 1);

namespace App\Manager;

use App\Entity\Task;
use App\Entity\TaskList;
use Doctrine\Common\Persistence\ObjectManager;

final class TaskListManager
{
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return TaskList[]
     */
    public function getLists(): array
    {
        return $this->entityManager->getRepository(TaskList::class)->findAll();
    }

    public function createList(TaskList $taskList): void
    {
        $this->entityManager->persist($taskList);
        $this->entityManager->flush();
    }

    public function addTaskToList(TaskList $taskList, Task $task): void
    {
        $taskList->addTask($task);

        $this->entityManager->flush();
    }
}
