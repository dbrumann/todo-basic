<?php declare(strict_types = 1);

namespace App\Tests\Manager;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Manager\TaskListManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;

/**
 * @group functional
 */
class TaskListManagerTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private static $entityManager;

    /**
     * @var TaskListManager
     */
    private static $taskListManager;

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
        static::$taskListManager = new TaskListManager($entityManager);
    }

    public function test_it_persists_a_list()
    {
        $list = new TaskList();
        $list->setTitle('Test list');

        static::$taskListManager->createList($list);

        $this->assertGreaterThan(0, $list->getId());
    }

    public function test_it_returns_lists()
    {
        $persistedList = $this->createAndPersistList();

        $lists = static::$taskListManager->getLists();

        $this->assertGreaterThan(0, count($lists));
        $this->assertContainsOnlyInstancesOf(TaskList::class, $lists);
        $this->assertContains($persistedList, $lists);
    }

    public function test_it_adds_task_to_list()
    {
        $persistedList = $this->createAndPersistList();

        $task = new Task($persistedList);
        $task->setTitle('Something to do');

        static::$taskListManager->addTaskToList($persistedList, $task);

        $this->assertGreaterThan(0, $task->getId());
        $this->assertCount(1, $persistedList->getTasks());
    }

    private function createAndPersistList(): TaskList
    {
        $list = new TaskList();
        $list->setTitle('Test list');

        static::$entityManager->persist($list);
        static::$entityManager->flush();

        return $list;
    }
}
