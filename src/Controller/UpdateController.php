<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Team;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\StatsManager;
use App\Services\MatchsDeLaNuit;
 



class UpdateController extends AbstractController
{
    /**
     * @Route("/update", name="update")
     */
    public function updateStats( MatchsDeLaNuit $MatchsDeLaNuit, StatsManager $StatsManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $teamrepository = $this->getDoctrine()->getRepository(Team::class);
        $playerrepository = $this->getDoctrine()->getRepository(Player::class);

        $teams = $teamrepository->findAll();
        $todayGames = $MatchsDeLaNuit -> MatchsDeLanuit();
        $tomorrowGames = $MatchsDeLaNuit -> MatchsDeDemain();
        $games=array_merge($todayGames,$tomorrowGames);
        for ($i=0; $i < count($games) ; $i++) {
            $hteamid=$games[$i]['HomeTeamId'];
            $ateamid=$games[$i]['AwayTeamId'];
            $hteam= $teamrepository->findOneBy(['teamId'=>$hteamid]);
            $ateam= $teamrepository->findOneBy(['teamId'=>$ateamid]);
            
            if($hteam == null){
                $hteam = new Team();
                $hteam->setTeamId($hteamid);
            }
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
                $player=$playerrepository->findOneBy(['playerId'=>$players[$j]['id']]);
                if($player == null){
                    $player = new Player();
                    $player->setPlayerId($players[$j]['id']);
                    $hteam->addPlayer($player);
                }
                if ($j<3) {
                    $playerLast5= $StatsManager->playerLast5Games($hteamid,$players5[$j]['id']);
                    $player->setLast5Games($playerLast5);
                } 
                $player->setTeam($hteam);
                $player->setStats($players[$j]);
                if(isset($players5[$j])){$player->setStats5($players5[$j]);}
                $entityManager->persist($player);
            }
            $entityManager->persist($hteam);

            if($ateam == null)
            {
                $ateam = new Team();
                $ateam->setTeamId($ateamid);
            }
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
                $player=$playerrepository->findOneBy(['playerId'=>$players[$j]['id']]);
                if($player == null){
                    $player = new Player();
                    $player->setPlayerId($players[$j]['id']);
                    $ateam->addPlayer($player);
                }
                if ($j<3) {
                    $playerLast5= $StatsManager->playerLast5Games($ateamid,$players5[$j]['id']);
                    $player->setLast5Games($playerLast5);
                } 
                $player->setTeam($ateam);
                $player->setStats($players[$j]);
                if(isset($players5[$j])){$player->setStats5($players5[$j]);}
                $entityManager->persist($player);
            }
            $entityManager->persist($ateam);
            $entityManager->flush();
        }      
        return $this->redirectToRoute('main');
    }
}