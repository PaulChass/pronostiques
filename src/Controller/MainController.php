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
    public function faceAface(MatchsDeLaNuit $MatchsDeLaNuit, int $gameId, CommentFormBuilder $commentFormBuilder, Request $request)
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
$url = file_get_contents('https://www.youtube.com/results?search_query=Recap+'.$hname.'+VS+'.$aname.'+Full+Game+Highlights'); // Ont récupere tout le code xhtml de la page.
preg_match_all('`<a href="([^>]+)">[^<]+</a>`',$url,$liens); // Ont recherche tout les liens présent sur la page.
$count = count($liens[1]); // Nombre de liens trouvé
$link=substr($liens[1][1],9);
$link=substr($link, 0, 11);

        
$titles=array('Points','%TirAdv','Rebonds','Contres','Interceptions','Pdb','Passes','%Tir');
$data=array(115, 104, 97, 95, 104,106,101,112);

$graph = new RadarGraph (450,380);

$graph->title->Set('Par rapport aux moyennes de la ligue');
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);

$graph->SetTitles($titles);
$graph->SetCenter(0.5,0.55);
$graph->HideTickMarks();
$graph->SetColor('lightgreen@0.7');
$graph->axis->SetColor('darkgray');
$graph->grid->SetColor('darkgray');
$graph->grid->Show();

$graph->axis->title->SetFont(FF_ARIAL,FS_NORMAL,12);
$graph->axis->title->SetMargin(5);
$graph->SetGridDepth(DEPTH_BACK);
$graph->SetSize(0.6);

$plot = new RadarPlot($data);
$plot->SetColor('red@0.2');
$plot->SetLineWeight(1);
$plot->SetFillColor('red@0.7');

$plot->mark->SetType(MARK_IMG_SBALL,'red');

$graph->Add($plot);
// Display the graph
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
//Start buffering
ob_start();      
//Print the data stream to the buffer
$graph->img->Stream(); 
//Get the conents of the buffer
$image_data = ob_get_contents();
//Stop the buffer/clear it.
ob_end_clean();
//Set the variable equal to the base 64 encoded value of the stream.
//This gets passed to the browser and displayed.
$image = base64_encode($image_data);
      

        
        $form = $commentFormBuilder-> commentBuild($request,$gameId);
        $comments = $commentRepository->findBy(['game' => $game]);
        //$cotes = $StatsManager -> CotesFaceAFace($hometeam,$awayteam);
        return $this->render('face_a_face.html.twig', [
            'joueurs_domicile'=>$homeplayers,
            'joueurs_exterieur'=>$awayplayers,
            'matchs'=>$matchsDeLaNuit,
            'form'=> $form->createView(),
            'graph'=>$image,
            'comments' => $comments,
            'link'=>$link,
            'teams'=>$teams,
        ]);
    }
}