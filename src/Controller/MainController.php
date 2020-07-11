<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Player;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use Amenadiel\JpGraph\Graph\RadarGraph;
use Amenadiel\JpGraph\Plot\RadarPlot;
use App\Services\MatchsDeLaNuit;
use App\Services\GraphManager;
use App\Controller\CommentFormBuilder;



class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function homepage(MatchsDeLaNuit $MatchsDeLaNuit)
    {
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit();
 
        return $this->render('home.html.twig', [
            'matchs'=>$matchsDeLaNuit,
        ]);
    }

    /**
     * @Route("/face-a-face/{gameId}", name="face-a-face")
     */
    public function faceAface(MatchsDeLaNuit $MatchsDeLaNuit, int $gameId, CommentFormBuilder $commentFormBuilder, Request $request, GraphManager $graphManager)
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $repository = $this->getDoctrine()->getRepository(Game::class);
        $teamrepository = $this->getDoctrine()->getRepository(Team::class);
        $playerrepository = $this->getDoctrine()->getRepository(Player::class);
        $game = $repository->findOneBy(['gameId' => $gameId]);
        $hometeam = $game->getHometeamid();
        $awayteam = $game->getAwayteamid();
        $teams= $teamrepository->findBy(['game' => $game]);  
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit();   
        $homeplayers = $playerrepository -> findBy(['team'=>$teams[0]]);
        $awayplayers = $playerrepository -> findBy(['team'=>$teams[1]]);
        
        $image=$graphManager->player5Graph($homeplayers);
        $image2=$graphManager->player5Graph($awayplayers);
        $graph=[$image,$image2];
                
        $form = $commentFormBuilder-> commentBuild($request,$gameId);
        $comments = $commentRepository->findBy(['game' => $game]);
        //$cotes = $StatsManager -> CotesFaceAFace($hometeam,$awayteam);
        return $this->render('face_a_face.html.twig', [
            'joueurs_domicile'=>$homeplayers,
            'joueurs_exterieur'=>$awayplayers,
            'matchs'=>$matchsDeLaNuit,
            'form'=> $form->createView(),
            'graph'=>$graph,
            'game'=>$game,
            'comments' => $comments,
            'teams'=>$teams,
        ]);
    }

    /**
     * @Route("/signin", name="signin")
     */
    public function signin(MatchsDeLaNuit $MatchsDeLaNuit,Request $request, SignInFormBuilder $signInFormBuilder)
    {
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit();   
        $form = $signInFormBuilder -> signInBuild($request);
        return $this->render('signin.html.twig' ,[
            'matchs'=>$matchsDeLaNuit,
            'form'=> $form->createView(),

        ]);
    }

    
}