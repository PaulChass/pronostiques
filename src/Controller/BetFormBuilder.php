<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;




class BetFormBuilder extends AbstractController
{
    public function betBuild(Request $request )
    {
        $user = new user();       

        $form = $this->createFormBuilder($user)
        ->add('username', ChoiceType::class, [
                    'choices' => [
                        'Face a face'=>'faf' ,
                        'Mi temps / Fin de match'=>'MF']])

        ->add('password',ChoiceType::class, [
                    'choices' => [
                        '1'=> '1' ,
                        '2'=> '2'],
                    'expanded' => true    
                    ]
                ) 
        ->getForm();
        return $form;


            
            
            
    }
}