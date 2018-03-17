<?php declare(strict_types = 1);

namespace App\Tests\Manager;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Manager\TaskManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;

/**
 * @group functional
 */
class TaskManagerTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private static $entityManager;

    /**
     * @var TaskManager
     */
    private static $taskManager;

    public static function setUpBeforeClass()
    {
        $config = DoctrineTestHelper::createTestConfiguration();
        $config->setNamingStrategy(new UnderscoreNamingStrategy());

        $entityManager = DoctrineTestHelper::createTestEntityManager($config);
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema([
            $entityManager->getClassMetadata(TaskList::class),
            $entityManager->getClassMetadata(Task::class),
        ]);

        static::$entityManager = $entityManager;
        static::$taskManager = new TaskManager($entityManager);
    }

    public function test_it_updates_task_status()
    {
        $persistedTask = $this->createAndPersistTask();

        static::$taskManager->changeStatus($persistedTask);

        $this->assertTrue($persistedTask->isDone());
    }

    private function createAndPersistTask(): Task
    {
        $list = new TaskList();
        $task = new Task($list);
        $task->setTitle('Some task');
        $list->setTitle('Test list');
        $list->addTask($task);

        static::$entityManager->persist($list);
        static::$entityManager->flush();

        return $task;
    }
}
