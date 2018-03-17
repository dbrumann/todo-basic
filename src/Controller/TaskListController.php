<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Form\TaskListType;
use App\Form\TaskType;
use App\Manager\TaskListManager;
use App\Manager\TaskManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TaskListController extends AbstractController
{
    private $taskListManager;

    public function __construct(TaskListManager $taskListManager)
    {
        $this->taskListManager = $taskListManager;
    }

    /**
     * @Route("/", name="show_lists", methods={"GET"})
     */
    public function listTaskLists(): Response
    {
        return $this->render(
            'tasks/list.html.twig',
            [
                'lists' => $this->taskListManager->getLists(),
            ]
        );
    }

    /**
     * @Route("/lists/new", name="create_list", methods={"GET", "POST"})
     */
    public function createTaskList(Request $request): Response
    {
        $form = $this->createForm(TaskListType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskListManager->createList($form->getData());

            return $this->redirectToRoute('show_lists');
        }

        return $this->render(
            'tasks/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/lists/{id}", name="show_list", methods={"GET"})
     */
    public function showTaskList(TaskList $taskList): Response
    {
        return $this->render(
            'tasks/show.html.twig',
            [
                'list' => $taskList,
            ]
        );
    }

    /**
     * @Route("/lists/{id}/add", name="add_task", methods={"GET", "POST"})
     */
    public function addTask(Request $request, TaskList $taskList): Response
    {
        // Yuck, this should be done in the manager, but whatever
        $task = new Task($taskList);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskListManager->addTaskToList($taskList, $form->getData());

            return $this->redirectToRoute(
                'show_list',
                [
                    'id' => $taskList->getId(),
                ]
            );
        }

        return $this->render(
            'tasks/add.html.twig',
            [
                'list' => $taskList,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/tasks/{id}", name="update_task", methods={"POST"})
     */
    public function updateStatus(TaskManager $taskManager, Task $task): Response
    {
        $taskManager->changeStatus($task);

        return $this->redirectToRoute(
            'show_list',
            [
                'id' => $task->getList()->getId(),
            ]
        );
    }
}
