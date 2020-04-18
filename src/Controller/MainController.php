<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\StatsManager;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function homepage(StatsManager $StatsManager)
    {
        $matchsDeLaNuit = $StatsManager -> MatchsDeLanuit();
        return $this->render('matchs_de_la_nuit.html.twig', [
            'matchs'=>$matchsDeLaNuit,
        ]);
    }

    /**
     * @Route("/face-a-face/{hometeam}/{awayteam}", name="face-a-face")
     */
    public function faceAface(StatsManager $StatsManager, int $hometeam, int $awayteam)
    {
        $matchsDeLaNuit = $StatsManager -> MatchsDeLanuit();
        $homePlayers = $StatsManager -> players($hometeam);
        $awayPlayers= $StatsManager -> players($awayteam);
       $statsDomicile = $StatsManager -> teamStats($hometeam);
        $statsExterieur = $StatsManager -> teamStats($awayteam);
       // $statsDomicile = $StatsManager -> StatsEquipe($hometeam);
        //$statsExterieur = $StatsManager -> StatsEquipe($awayteam);
        $stats5Domicile = $StatsManager -> teamStats5($hometeam);
        $stats5Exterieur = $StatsManager -> teamStats5($awayteam);
        $infirmerieDomicile=$StatsManager-> injury($hometeam);
        $infirmerieExterieur=$StatsManager-> injury($awayteam);
        $statsHome = $StatsManager -> locationStats($hometeam,'Home');
        $statsRoad = $StatsManager -> locationStats($awayteam,'Road');

        //$cotes = $StatsManager -> CotesFaceAFace($hometeam,$awayteam);
        return $this->render('face_a_face.html.twig', [
            'stats_domicile'=>$statsDomicile,
            'stats_exterieur'=>$statsExterieur,
            'stats5_dom'=>$stats5Domicile,
            'stats5_ext'=>$stats5Exterieur,
            'statsHome'=>$statsHome,
            'statsRoad'=>$statsRoad,
            'joueurs_domicile'=>$homePlayers,
            'joueurs_exterieur'=>$awayPlayers,
            'infirmerie_domicile'=>$infirmerieDomicile,
            'infirmerie_exterieur'=>$infirmerieExterieur,
            'matchs'=>$matchsDeLaNuit
        ]);
        dump($statsDomicile);dump($statsExterieur);
    }
}