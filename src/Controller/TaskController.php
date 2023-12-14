<?php

namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use App\Services\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//для ограничения доступа
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/', name: 'app_task_')]

class TaskController extends AbstractController
{
    //Показ всех задач
    #[Route("/", name: "list")]
    public function list(TaskService $taskService): Response
    {
        // $task=$taskRepository->findAll();до
        $task = $taskService->getTaskList();

        return $this->render("task/list.html.twig", [
            'tasks' => $task,
        ]);
    }
    //подробнее о каждой записи
    #[Route("/view/{id}", name: "view")]
    public function view(int $id, TaskService $taskService): Response
    {

        $task = $taskService->getTaskView($id);
        $message = $taskService->getHappyMessage();


        return $this->render('task/view.html.twig', [
            'messa' => $message,
            'task' => $task,
            'category' => $task->getCategory(),
            'createdAt' => $task->getCreatedAt(),
            'Data' => $task->getData(),
        ]);
    }
    //удаление записи
    #[Route("/delete/{id}", name: "delete")]
    public function delete(int $id,TaskService $taskService): Response
    {

        $task = $taskService->TaskDelit($id);
        $this->addFlash("SUCCES", "Task with {$id} succesefull deleted");
        return $this->redirectToRoute('app_task_list');
    }

    //обновление записи
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, int $id, TaskService $taskService): Response
    {

        $task = $entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        $task = $taskService->updateTask($request, $id, $task);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_task_list');
        }


        return $this->render('task/edit.html.twig', [
            'form_edit' => $form->createView(),
        ]);
    }
    //создание записи
    #[Route('/create', name: 'create')]
    public function index(Request $request, TaskService $taskService): Response
    {

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form = $taskService->creatTask($request, $form);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', "Задача успешно создана");
            return $this->redirectToRoute('app_task_list');
        }
        return $this->render('task/create.html.twig', [
            'task_form' => $form
        ]);
    }
    
}