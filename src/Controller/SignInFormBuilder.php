<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


class SignInFormBuilder extends AbstractController
{
    public function signInBuild(Request $request)
    {
        $user = new user();       
        $form = $this->createFormBuilder($user)
        ->add('username', TextType::class, [
            'label' => 'Nom d\'utilisateur'])
        ->add('password', PasswordType::class, ['label'=>'Mot de passe'])
        ->add('save', SubmitType::class, ['label' => 'Connexion'])
        ->getForm();

    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $cryptedPassword = crypt($user->getPassword(),'165!.64sfhfhusbs2224-MonPetitGraindeSEL');
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $db_user = $userRepository->findOneBy(['username' => $user->getUsername()]);
            if($db_user==null){
                $_SESSION['error']='Nom d\'utilisateur incorrect. Veuillez réessayer ';
            }
            else{
                $db_cryptedPassword = $db_user->getPassword();
                if($db_cryptedPassword==$cryptedPassword)
                {
                    $_COOKIE['user']=$user->getUsername();
                    $_SESSION['error']='';
                }            
                else{$_SESSION['error']='Mot de passe incorrect. Veuillez réessayer ';
                } 
            }
        }
      

    return $form;
    }
}
