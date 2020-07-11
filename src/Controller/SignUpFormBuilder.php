<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


class SignUpFormBuilder extends AbstractController
{
    public function signUpBuild(Request $request, $matchsDeLaNuit)
    {
        $user = new user();       
        $form = $this->createFormBuilder($user)
        ->add('username', TextType::class, [
            'label' => 'Nom d\'utilisateur'])
        ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
        ->add('save', SubmitType::class, ['label' => 'Creer mon compte'])
        ->getForm();

    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $cryptedPassword = crypt($user->getPassword(),'165!.64sfhfhusbs2224-MonPetitGraindeSEL');
            $user->setPassword($cryptedPassword);
            $entityManager->persist($user);
            $entityManager->flush();
            $_COOKIE['user']=$user->getUsername();
            $_SESSION['user']=$user->getUsername();
        }
      

    return $form;
    }
}
