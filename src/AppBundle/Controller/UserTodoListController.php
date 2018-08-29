<?php

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Todo;
use AppBundle\Form\TodoType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class UserTodoListController extends Controller
{
    /**
     * @Route("/todo-liste",name="todo_list")
     */
    public function listAction()
    {
        $userId = $this->getUser();

        $todos = $this->getDoctrine()->getRepository(Todo::class)->findBy(
            ['user' => $userId],
            ['id' => 'DESC']
        );

        return $this->render('todo/list.html.twig', array(
            'todos' => $todos,
        ));
    }

    /**
     * @Route("/todo-liste/ajout",name="todo_list_new")
     */
    public function newAction(
        Request $request,
        ObjectManager $entityManager
        ) {
        // create empty todo
        $todo = new Todo();

        // create empty form
        $form = $this->createForm(TodoType::class, $todo)->handleRequest($request);

        // if form is ok
        if ($form->isSubmitted() && $form->isValid()) {
            $todo->setUser($this->getUser());
            $entityManager->persist($todo);
            $entityManager->flush();

            //add flash message
            $this->addFlash('success', 'Todo ajoutée avec succès');
            //redirect to listing
            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/todo-liste/modification/{id}",name="todo_list_edit")
     */
    public function editAction(
        ObjectManager $entityManager,
         $id,
        Request $request
        ) {
        // retrieve todo from db
        $todoToEdit = $entityManager->getRepository(Todo::class)->find($id);

        // generate the form
        $form = $this->createForm(TodoType::class, $todoToEdit)->handleRequest($request);

        //if form is ok
        if ($form->isSubmitted() && $form->isValid()) {
            // save changes
            $entityManager->flush();

            // add success message and redirect to todo list
            $this->addFlash('success', 'Votre Todo a été mise à jour');

            return $this->redirectToRoute('todo_list');
        }

        return $this->render('todo/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/todo-liste/suppression/{id}",name="todo_list_delete")
     */
    public function deleteAction(
        ObjectManager $entityManager,
        $id
        ) {
        // retrieve $todo from db
        $todoToDelete = $this->getDoctrine()->getRepository(Todo::class)->find($id);
        //delete from database
        $entityManager->remove($todoToDelete);
        $entityManager->flush();

        // add success message
        $this->addFlash('success', 'Votre Todo a bien été supprimée');
        //redirect to todo-list
        return $this->redirectToRoute('todo_list');
    }
}
