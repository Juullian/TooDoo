<?php

namespace App\Controller;

use App\Entity\ItemsToDoList;
use App\Entity\TodoItem;
use App\Form\TodoFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use App\Repository\ItemsToDoListRepository;



class TodoController extends AbstractController
{   

    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new TodoItem();
        $form = $this->createForm(TodoFormType::class, $todo);

        $repo = $entityManager->getRepository(ItemsToDoList::class);
        $items = $repo->findAll();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            // $task = $form->getData();
            $todoitem = $form["todoname"]->getData();

            // adds to Database.
            $itemToDoList = new ItemsToDoList();
            $itemToDoList->setItem($todoitem);
            $entityManager->persist($itemToDoList);
            $entityManager->flush();
            
            unset($todo);
            unset($form);

            $todo = new TodoItem();
            $form = $this->createForm(TodoFormType::class, $todo);

            return $this->redirectToRoute("app_todo");
        }

        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'todo_form' => $form->createView(),
            "items" => $items
        ]);
    }


    #[Route('/delete/{id}', name:"delete_item")]
    public function deleteItem($id, EntityManagerInterface $entityManager) {
        $product = $entityManager->getRepository(ItemsToDoList::class)->find($id);
        if($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }

    #[Route('/status/{id}', name:"status_item")]
    public function statusItem($id, EntityManagerInterface $entityManager) {
        $product = $entityManager->getRepository(ItemsToDoList::class)->find($id);
        if($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }
}