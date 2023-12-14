<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TaskRepository;

class TaskService
{
    private $taskRepository;
    private $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function getTaskList(): array
    {
        return $this->taskRepository->findAll();
    }

    public function getTaskView(int $id)
    {
        $task = $this->taskRepository->find($id);
        if (!$task) {
            throw new \Exception("Задача {$id} не найдена");
        }
        return $task;
    }

    public function TaskDelit(int $id)
    {
        // Находим задачу по ID
        $task = $this->taskRepository->find($id);

        if (!$task) {
            throw new \Exception("Задача с ID {$id} не найдена");
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $task;
    }

    public function creatTask($request, $form)
    {

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->entityManager->persist($task);
            $this->entityManager->flush();

        }

        return $form;
    }

    public function updateTask($request, $id, $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function getHappyMessage(): string
    {
        $messages = [
            'You did it! You updated the system!',
            'That was one of the coolest updates!',
            'Great work! Keep going!',
        ];
        $index = array_rand($messages);
        return $messages[$index];
    }
}