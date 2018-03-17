<?php declare(strict_types = 1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group integration
 */
class TaskListControllerTest extends WebTestCase
{
    public function test_frontpage_is_accessible()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Task Lists")')->count());
    }

    public function test_list_can_be_created()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lists/new');

        $form = $crawler
            ->selectButton('Create list')
            ->form(
                [
                    'task_list[title]' => 'Some task list',
                ]
            )
        ;
        $client->submit($form);

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('li:contains("Some task list")')->count());
    }

    /**
     * @depends test_list_can_be_created
     */
    public function test_displays_list()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lists/1');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h2:contains("Tasks")')->count());
    }

    /**
     * @depends test_list_can_be_created
     */
    public function test_task_can_be_added_to_list()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lists/1/add');

        $form = $crawler
            ->selectButton('Add task')
            ->form(
                [
                    'task[title]' => 'Something to do',
                ]
            )
        ;
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('li:contains("Something to do")')->count());
    }

    /**
     * @depends test_task_can_be_added_to_list
     */
    public function test_task_can_be_marked_as_done()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lists/1');

        $form = $crawler->selectButton('◻️')->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('li:contains("☑️")')->count());
    }

    /**
     * @depends test_task_can_be_marked_as_done
     */
    public function test_task_can_be_reopened()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lists/1');

        $form = $crawler->selectButton('☑️')->form();
        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('li:contains("◻️")')->count());
    }
}
