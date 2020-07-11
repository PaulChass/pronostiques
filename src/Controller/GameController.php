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
            


            $hteam= new Team();
            $hteam->setGame($game);
            $hteam->setLogourl($games[$i]['HomeLogoUrl']);
            $hteam->setLastUpdate(new \DateTime());
            $injury=$StatsManager->injury($hteamid);
            $hteam->setInjuries($injury);
            $twitter=$StatsManager->twitter($hteamid);
            $hteam->setTwitter($twitter);
            $stats=$StatsManager->teamStats($hteamid);
            $hteam->setStats($stats);
            $stats5 = $StatsManager -> teamStats5($hteamid);
            $hteam->SetStats5($stats5);
            $statsLocation = $StatsManager -> locationStats($hteamid,'Home');
            $last5=$StatsManager->last5games($hteamid);
            $hteam->setLast5games($last5);
            $advancedStats = $StatsManager->advancedTeamStats($hteamid);
            $hteam->SetAdvancedStats($advancedStats);
            $hteam->SetStatsLocation($statsLocation);
            $players= $StatsManager ->players($hteamid);
            $players5 = $StatsManager-> players5($hteamid);
            for ($j=0; $j < count($players) ; $j++) { 
                $player= new Player();
                if ($j<3) {
                    $playerLast5= $StatsManager->playerLast5Games($hteamid,$players5[$j]['id']);
                } 
                $player->setTeam($hteam);
                $player->setStats($players[$j]);
                if(isset($playerLast5)){$player->setLast5Games($playerLast5);}
                if(isset($players5[$j])){$player->setStats5($players5[$j]);}
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
            $twitter=$StatsManager->twitter($ateamid);
            $ateam->setTwitter($twitter);
            $stats=$StatsManager->teamStats($ateamid);
            $ateam->setStats($stats);
            $stats5 = $StatsManager -> teamStats5($ateamid);
            $ateam->SetStats5($stats5);
            $last5=$StatsManager->last5games($ateamid);
            $ateam->setLast5games($last5);
            $advancedStats = $StatsManager->advancedTeamStats($ateamid);
            $ateam->SetAdvancedStats($advancedStats);
            $statsLocation = $StatsManager -> locationStats($ateamid,'Road');
            $ateam->SetStatsLocation($statsLocation);


            $players= $StatsManager ->players($ateamid);
            $players5 = $StatsManager-> players5($ateamid);
            for ($j=0; $j < count($players) ; $j++) { 
                $player= new Player();
                if($j<3) {
                        $playerLast5= $StatsManager->playerLast5Games($ateamid,$players5[$j]['id']);
                }
                $player->setTeam($ateam);
                $player->setStats($players[$j]);
                if(isset($playerLast5)){$player->setLast5Games($playerLast5);}
                if(isset($players5[$j])){$player->setStats5($players5[$j]);}
                $entityManager->persist($player);
                $ateam->addPlayer($player);
            }
            echo $i;
            $game->addTeam($ateam);
            $astats=$ateam->getStats();
            $hstats=$hteam->getStats();
            $videoId = $MatchsDeLaNuit -> getLink($hstats['Team'],$astats['Team']);
            $game->setVideoId($videoId);
            $entityManager->persist($ateam);
            $entityManager->persist($game);

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
