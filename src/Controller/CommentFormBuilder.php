<?php

namespace App\Controller;

use App\Controller\GameController;
use App\Entity\Comment;
use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommentFormBuilder extends AbstractController
{
    public function commentBuild(Request $request, $gameId)
    {
        // creates a task object and initializes some data for this example
        $comment = new comment();
        
        $comment->setUsername('Anonyme');
        if(isset($_COOKIE['user']))
        {        
            $comment->setUsername($_COOKIE['user']);
        }
        $comment->setPublishDate(new \DateTime());
        $repository = $this->getDoctrine()->getRepository(Game::class);
        $game = $repository->findOneBy(['gameId' => $gameId]);
        $comment->setGameId($game);
        $form = $this->createFormBuilder($comment)
            ->add('username', TextType::class, ['label' => 'Votre Nom'])
            ->add('bet', TextType::class, ['label' => 'Votre bet'])
            ->add('message', TextareaType::class, ['label' => 'Pourquoi ?'] )
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();
        
            
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $comment = $form->getData();


            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

        }
        return $form;
    }
}