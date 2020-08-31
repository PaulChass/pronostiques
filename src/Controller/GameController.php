<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Player;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MatchsDeLaNuit;

class GameController extends AbstractController
{
    /**
     * @Route("/game/{gameId}", name="game")
     */
    public function createGames(int $gameId, MatchsDeLaNuit $MatchsDeLaNuit): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $todayGames = $MatchsDeLaNuit -> MatchsDeLanuit();
        $tomorrowGames = $MatchsDeLaNuit -> MatchsDeDemain();
        $games=array_merge($todayGames,$tomorrowGames);

        $gamerepository = $this->getDoctrine()->getRepository(Game::class);
        $teamrepository = $this->getDoctrine()->getRepository(Team::class);
        for ($i=0; $i < count($games) ; $i++) {
            $game=$gamerepository->findOneBy(['gameId' => $games[$i]['GameId']]);
            $hteamid=$games[$i]['HomeTeamId'];
            $ateamid=$games[$i]['AwayTeamId'];
            if($game==null){
            $game = new Game();
            $game->setDatetime(new \DateTime($games[$i]['Time']));
            $game->setAwayteamid($ateamid);
            $game->setHometeamid($hteamid);
            $game->setGameid($games[$i]['GameId']);
            $hteam= $teamrepository->findOneBy(['teamId'=>$hteamid]);
            $hstats = $hteam->getStats();
            $ateam = $teamrepository->findOneBy(['teamId'=>$ateamid]);
            $astats = $ateam->getStats();
            $videoId = $MatchsDeLaNuit -> getLink($hstats['Team'],$astats['Team']);
            $game->setVideoId($videoId);
            }            
            $entityManager->persist($game);
        }
        $entityManager->flush();

        return $this->redirectToRoute('face-a-face',['gameId' =>  $gameId]);
    }
} 