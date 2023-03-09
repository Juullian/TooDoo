<?php

namespace App\Controller;

use App\Entity\ItemsFinsihed;
use App\Entity\ItemsToDoList;
use App\Entity\TodoItem;
use App\Form\TodoFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class TodoController extends AbstractController
{   

    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new TodoItem();
        $form = $this->createForm(TodoFormType::class, $todo);

        $repo = $entityManager->getRepository(ItemsToDoList::class);
        $items = $repo->findAll();

        $repo2 = $entityManager->getRepository(ItemsFinsihed::class);
        $items_finished = $repo2->findAll();

        dump($items_finished);

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
            "items" => $items,
            "items_finished" => $items_finished
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
    
    #[Route('/delete_finished/{id}', name:"delete_finished_item")]
    public function deleteFinishedItem($id, EntityManagerInterface $entityManager) {
        $product = $entityManager->getRepository(ItemsFinsihed::class)->find($id);
        if($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }

    #[Route('/status/{id}', name:"status_item")]
    public function statusItem($id, EntityManagerInterface $entityManager, EntityManagerInterface $entityManager2) {
        $not_made = $entityManager->getRepository(ItemsToDoList::class)->find($id);
        $old_item = $not_made->getItem();
        $new_finished = $entityManager2->getRepository(ItemsFinsihed::class);
        if($not_made) {
            $entityManager->remove($not_made);
            $entityManager->flush();
            dump($old_item);    
            $item_finsih = new ItemsFinsihed();
            $item_finsih->setItem($old_item);
            $entityManager2->persist($item_finsih);
            $entityManager2->flush();

            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }

    #[Route('/status_finished/{id}', name:"status_finished_item")]
    public function statusFinishedItem($id, EntityManagerInterface $entityManager, EntityManagerInterface $entityManager2) {
        $not_made = $entityManager->getRepository(ItemsFinsihed::class)->find($id);
        $old_item = $not_made->getItem();
        $new_finished = $entityManager2->getRepository(ItemsToDoList::class);
        if($not_made) {
            $entityManager->remove($not_made);
            $entityManager->flush();
            dump($old_item);    
            $item_finsih = new ItemsToDoList();
            $item_finsih->setItem($old_item);
            $entityManager2->persist($item_finsih);
            $entityManager2->flush();

            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }
}