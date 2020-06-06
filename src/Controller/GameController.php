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
use App\Services\StatsManager;
use App\Services\MatchsDeLaNuit;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game")
     */
    public function createGames(StatsManager $StatsManager, MatchsDeLaNuit $MatchsDeLaNuit): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $games = $MatchsDeLaNuit -> MatchsDeLanuit();
        for ($i=0; $i < count($games) ; $i++) { 
            $hteamid=$games[$i]['HomeTeamId'];
            $ateamid=$games[$i]['AwayTeamId'];

            $game = new Game();
            $game->setDatetime(new \DateTime($games[$i]['Time']));
            $game->setAwayteamid($ateamid);
            $game->setHometeamid($hteamid);
            $game->setGameid($games[$i]['GameId']);
            $entityManager->persist($game);


            $hteam= new Team();
            $hteam->setGame($game);
            $hteam->setLogourl($games[$i]['HomeLogoUrl']);
            $hteam->setLastUpdate(new \DateTime());
            $injury=$StatsManager->injury($hteamid);
            $hteam->setInjuries($injury);
            $stats=$StatsManager->teamStats($hteamid);
            $hteam->setStats($stats);
            $stats5 = $StatsManager -> teamStats5($hteamid);
            $hteam->SetStats5($stats5);
            $statsLocation = $StatsManager -> locationStats($hteamid,'Home');
            $last5=$StatsManager->last5games($hteamid);
            $hteam->setLast5games($last5);
            $hteam->SetStatsLocation($statsLocation);
            $players= $StatsManager ->players($hteamid);
            for ($j=0; $j < count($players) ; $j++) { 
                $player= new Player();
                $player->setTeam($hteam);
                $player->setStats($players[$j]);
                $entityManager->persist($player);
                $hteam->addPlayer($player);
            }
            echo $i;
            $game->addTeam($hteam);
            $entityManager->persist($hteam);

            $ateam= new Team();
            $ateam->setGame($game);
            $ateam->setLogourl($games[$i]['AwayLogoUrl']);
            $ateam->setLastUpdate(new \DateTime());
            $injury=$StatsManager->injury($ateamid);
            $ateam->setInjuries($injury);
            $stats=$StatsManager->teamStats($ateamid);
            $ateam->setStats($stats);
            $stats5 = $StatsManager -> teamStats5($ateamid);
            $ateam->SetStats5($stats5);
            $last5=$StatsManager->last5games($ateamid);
            $ateam->setLast5games($last5);
            $statsLocation = $StatsManager -> locationStats($ateamid,'Road');
            $ateam->SetStatsLocation($statsLocation);


            $players= $StatsManager ->players($ateamid);
            for ($j=0; $j < count($players) ; $j++) { 
                $player= new Player();
                $player->setTeam($ateam);
                $player->setStats($players[$j]);
                $entityManager->persist($player);
                $ateam->addPlayer($player);
            }
            echo $i;
            $game->addTeam($ateam);
            $entityManager->persist($ateam);


            $entityManager->flush();
        }
            
            

                //$hteam =new Team(hid)
                //setLogoUrl
                //$hteam->setGame($game)
                
                //$Stat= new Stat()
                //$hteam->setStat($Stat)

            
            // for Players in team
            //$player = new player();
            //
            

            

            // actually executes the queries (i.e. the INSERT query
    
        echo "Les matchs équipes et joueurs ont été crées et enregistrés dans la BDD";
        die;

    }

    

    
}
