<?php declare(strict_types = 1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\TaskList;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */
class TaskListTest extends TestCase
{
    public function test_get_open_tasks_filters_open_tasks()
    {
        $list = new TaskList();
        $list->setTitle('List with 1 open and 1 done task');
        $openTask = new Task($list);
        $openTask->setTitle('open task');
        $doneTask = new Task($list);
        $doneTask->setTitle('done task');
        $doneTask->setDone();
        $list->addTask($openTask);
        $list->addTask($doneTask);

        $openTasks = $list->getOpenTasks();

        $this->assertInternalType('array', $openTasks);
        $this->assertContainsOnlyInstancesOf(Task::class, $openTasks);
        $this->assertContains($openTask, $openTasks);
        $this->assertNotContains($doneTask, $openTasks);
    }

    public function test_get_done_tasks_filters_done_tasks()
    {
        $list = new TaskList();
        $list->setTitle('List with 1 open and 1 done task');
        $openTask = new Task($list);
        $openTask->setTitle('open task');
        $doneTask = new Task($list);
        $doneTask->setTitle('done task');
        $doneTask->setDone();
        $list->addTask($openTask);
        $list->addTask($doneTask);

        $doneTasks = $list->getDoneTasks();

        $this->assertInternalType('array', $doneTasks);
        $this->assertContainsOnlyInstancesOf(Task::class, $doneTasks);
        $this->assertContains($doneTask, $doneTasks);
        $this->assertNotContains($openTask, $doneTasks);
    }
}
