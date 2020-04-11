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
        
        $statsDomicile = $StatsManager -> StatsEquipe($hometeam);
        $statsExterieur = $StatsManager -> StatsEquipe($awayteam);
        $cotes = $StatsManager -> CotesFaceAFace($hometeam,$awayteam);
        dump($statsDomicile);dump($statsExterieur);dump($cotes);die;
    }
}