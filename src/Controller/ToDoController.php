<?php

namespace App\Controller;

use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class ToDoController extends AbstractController
{

    // Return all tasks
    #[Route('/api/tasks', name: 'tasks', methods : ['GET'])]
    public function getAllTasks(TodoRepository $todoRepository, SerializerInterface $serializer): JsonResponse
    {
        $taskList = $todoRepository->findAll();

        $jsonTaskList = $serializer->serialize($taskList, 'json');

        return new JsonResponse(
            $jsonTaskList,
            Response::HTTP_OK, [], true
        );
    }

    //return task id
    #[Route('/api/task/{id}', name: 'task_id', methods : ['GET'])]
    public function getTaskById(TodoRepository $todoRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $task = $todoRepository->find($id);

        if ($task){
            $jsonTask = $serializer->serialize($task, 'json');
            return new JsonResponse( $jsonTask , Response::HTTP_OK , [] , true);
        }
        return new JsonResponse(null,Response::HTTP_NOT_FOUND);
    }

    //Delete task
    #[Route('/api/task/{id}', name: 'task_delete', methods : ['DELETE'])]
    public function deleteTask(EntityManagerInterface $entityManager, TodoRepository $todoRepository, int $id): JsonResponse
    {
        $task = $todoRepository->find($id);

        if ($task) {
            $entityManager->remove($task);
            $entityManager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(['message' => 'La tâche n\'a pas été trouvée'], Response::HTTP_NOT_FOUND);
    }


}
