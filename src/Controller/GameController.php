<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function createGame(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $game = new Game();
        $game->setDatetime(new \DateTime());
        $game->setawayteamid(1);
        $game->sethometeamid(1);//$datetime;$hometeamid;$awayteamid;$odds;
         // tell Doctrine you want to (eventually) save the Product (no queries yet)
         $entityManager->persist($game);

         // actually executes the queries (i.e. the INSERT query)
         $entityManager->flush();
 
         return new Response('Saved new product with id '.$game->getId());
    }
}
