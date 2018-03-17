<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class TaskList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="50")
     *
     * @ORM\Column(length=50)
     */
    private $title = '';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="list", cascade={"all"})
     * @ORM\OrderBy({"done": "ASC"})
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks->toArray();
    }

    /**
     * @return Task[]
     */
    public function getOpenTasks(): array
    {
        return $this->tasks
            ->filter(
                function (Task $task) {
                    return !$task->isDone();
                }
            )
            ->toArray()
        ;
    }

    /**
     * @return Task[]
     */
    public function getDoneTasks(): array
    {
        return $this->tasks
            ->filter(
                function (Task $task) {
                    return $task->isDone();
                }
            )
            ->toArray()
        ;
    }

    public function addTask(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
        }
    }
}
