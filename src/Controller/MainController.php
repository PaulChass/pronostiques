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
 
        return $this->render('matchs_de_la_nuit.html.twig', [
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


$hname = str_replace(' ', '+', $teams[0]->getStats()['Team']);
$aname = str_replace(' ', '+', $teams[1]->getStats()['Team']);  

$link=$MatchsDeLaNuit->getlink($hname,$aname);
for ($i=0; $i < 10; $i++) { 
    if($link===""){
        $link=$MatchsDeLaNuit->getlink($hname,$aname);
    } 
    else{$i=1000;}    
}

 
 
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
            'comments' => $comments,
            'link'=>$link,
            'teams'=>$teams,
        ]);
    }
}