<?php

namespace App\Controller;

use App\Entity\ItemsFinsihed;
use App\Entity\ItemsToDoList;
use App\Entity\TodoItem;
use App\Entity\TodoListItem;
use App\Entity\TodoListItemFinished;
use App\Form\TodoFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\VarDumper\Cloner\Data;

class TodoController extends AbstractController
{   

    #[Route('/', name: 'app_todo')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserInterface $useracc): Response
    {
        dump($useracc);
        dump($entityManager->getRepository(TodoListItem::class)->findBy(["user" => $useracc->getEmail()]));
        $todo = new TodoItem();
        $form = $this->createForm(TodoFormType::class, $todo);

        //$repo = $entityManager->getRepository(ItemsToDoList::class);
        $items = $entityManager->getRepository(TodoListItem::class)->findBy(["user" => $useracc->getEmail()]);

        //$repo2 = $entityManager->getRepository(ItemsFinsihed::class);
        $items_finished = $entityManager->getRepository(TodoListItemFinished::class)->findBy(["user" => $useracc->getEmail()]);

        dump($items_finished);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            // $task = $form->getData();
            $todoitem = $form["todoname"]->getData();

            // adds to Database.
            $itemToDoList = new TodoListItem();
            $itemToDoList->setItem($todoitem);
            $itemToDoList->setUser($useracc->getEmail());
            $entityManager->persist($itemToDoList);
            $entityManager->flush();
            
            unset($todo);
            unset($form);

            //$todo = new TodoItem();
            //$form = $this->createForm(TodoFormType::class, $todo);

            return $this->redirectToRoute("app_todo");
        }


        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'todo_form' => $form->createView(),
            "items" => $items,
            "items_finished" => $items_finished,
        ]);
    }


    #[Route('/delete/{id}', name:"delete_item")]
    public function deleteItem($id, EntityManagerInterface $entityManager) {
        $product = $entityManager->getRepository(TodoListItem::class)->find($id);
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
        $product = $entityManager->getRepository(TodoListItemFinished::class)->find($id);
        if($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }

    #[Route('/status/{id}', name:"status_item")]
    public function statusItem($id, EntityManagerInterface $entityManager, EntityManagerInterface $entityManager2, UserInterface $useracc) {
        $product = $entityManager->getRepository(TodoListItem::class)->find($id);
        $old_item = $product->getItem();
        if($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            dump($old_item);    
            $item_finsih = new TodoListItemFinished();
            $item_finsih->setItem($old_item);
            $item_finsih->setUser($useracc->getEmail());
            $entityManager2->persist($item_finsih);
            $entityManager2->flush();

            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }

    #[Route('/status_finished/{id}', name:"status_finished_item")]
    public function statusFinishedItem($id, EntityManagerInterface $entityManager, EntityManagerInterface $entityManager2, UserInterface $useracc) {
        $product = $entityManager->getRepository(TodoListItem::class)->find($id);
        $old_item = $product->getItem();
        if($product) {
            $entityManager->remove($product);
            $entityManager->flush();
            dump($old_item);    
            $item_finsih = new TodoListItem();
            $item_finsih->setItem($old_item);
            $item_finsih->setUser($useracc->getEmail());
            $entityManager2->persist($item_finsih);
            $entityManager2->flush();

            return $this->redirectToRoute("app_todo");
        } else {
        return $this->redirectToRoute("app_todo");
        }
    }

    #[Route("/delete_all", name: "deleteall")]
    public function deleteAll(EntityManagerInterface $entityManager, UserInterface $useracc) {
        $repo = $entityManager->getRepository(TodoListItem::class)->findBy(["user" => $useracc->getEmail()]);
        foreach($repo as $item){
            $del = $entityManager->getRepository(TodoListItem::class)->find($item->getId());
            $entityManager->remove($del);
            $entityManager->flush();
        } 

        $repo = $entityManager->getRepository(TodoListItemFinished::class)->findBy(["user" => $useracc->getEmail()]);
        foreach($repo as $item){
            $del = $entityManager->getRepository(TodoListItemFinished::class)->find($item->getId());
            $entityManager->remove($del);
            $entityManager->flush();
        } 
        return $this->redirectToRoute("app_todo");
    }

    #[Route("/delete_all_finished", name: "deleteallfinished")]
    public function deleteAllFinished(EntityManagerInterface $entityManager, UserInterface $useracc) {
        $repo = $entityManager->getRepository(TodoListItemFinished::class)->findBy(["user" => $useracc->getEmail()]);
        foreach($repo as $item){
            $del = $entityManager->getRepository(TodoListItemFinished::class)->find($item->getId());
            $entityManager->remove($del);
            $entityManager->flush();
        } 
        return $this->redirectToRoute("app_todo");
    }
}