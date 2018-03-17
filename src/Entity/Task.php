<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaskList", inversedBy="tasks")
     */
    private $list;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="100")
     *
     * @ORM\Column(length=100)
     */
    private $title = '';

    /**
     * @ORM\Column(name="status", type="boolean")
     */
    private $done = false;

    public function __construct(TaskList $list)
    {
        $this->list = $list;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getList(): TaskList
    {
        return $this->list;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function setDone(): void
    {
        $this->done = true;
    }

    public function setOpen(): void
    {
        $this->done = false;
    }
}
