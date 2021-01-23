<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class TodoController extends AbstractController
{
    /**
     * @Route("/api/todos",name="api_todo", methods={"GET"})
     */
    public function __invoke (): JsonResponse
    {
        return new JsonResponse([
            ['name' => 'todo_1'],
            ['name' => 'todo_2'],
            ['name' => 'todo_3'],
        ]);
    }
}
