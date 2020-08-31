<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Player;
use App\Entity\User;
use App\Entity\Bet;


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
use App\Services\StatsManager;

use App\Controller\CommentFormBuilder;
use App\Controller\BetFormBuilder;
use Symfony\Component\Form\FormBuilderInterface;




class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function homepage(MatchsDeLaNuit $MatchsDeLaNuit, StatsManager $StatsManager)
    {
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit();
        $matchsDeDemain = $MatchsDeLaNuit -> MatchsDeDemain();
        $mybets=[];$roi=0;$bilan='';$total='';
        if(isset($_COOKIE['user']))
        {
            $userrepository = $this->getDoctrine()->getRepository(User::class);
            $gamerepository = $this->getDoctrine()->getRepository(Game::class);
            $user = $userrepository->findOneBy(['username' => $_COOKIE['user']]);
            $bets = $user->getBets();
            $return=0;$invested=0;$won=0;
            foreach($bets as $bet)
            {
                $game = $gamerepository->findOneBy(['gameId' => $bet->getGameId()]);
                $h=$StatsManager->getAbvFromId($game->getHometeamId());
                $a=$StatsManager->getAbvFromId($game->getAwayteamId());
                if ($bet->getStatus()==1) {
                    $return = $return + $bet->getOdd();
                    $won++;
                }
                if ($bet->getStatus()!=0){$invested= $invested+1;}
                $mybet = [$h,$a,$bet->getBet1N2(),$bet->getOdd(),$bet->getStatus()];
                array_push($mybets,$mybet);

            }
            $bilan=[$won,$invested];
            $roi= $return/$invested;
            $total= $return-$invested;
        }

        return $this->render('home.html.twig', [
            'bets'=>$mybets,
            'roi'=>$roi,
            'bilan'=>$bilan,
            'total'=>$total,
            'matchs'=>$matchsDeLaNuit,
            'tomorrow_matchs'=>$matchsDeDemain
        ]);
    }

    /**
     * @Route("/comparaison/{gameId}", name="face-a-face")
     */
    public function faceAface(MatchsDeLaNuit $MatchsDeLaNuit, int $gameId, CommentFormBuilder $commentFormBuilder, Request $request, GraphManager $graphManager, BetFormBuilder $betFormBuilder )
    {
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $repository = $this->getDoctrine()->getRepository(Game::class);
        $teamrepository = $this->getDoctrine()->getRepository(Team::class);
        $playerrepository = $this->getDoctrine()->getRepository(Player::class);
        $game = $repository->findOneBy(['gameId' => $gameId]);
        if($game!=null){
        $hometeam = $game->getHometeamid();
        $awayteam = $game->getAwayteamid();
        $teams= [$teamrepository->findOneBy(['teamId' => $hometeam]),$teamrepository->findOneBy(['teamId' => $awayteam])];  
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit();   
        $matchsDeDemain = $MatchsDeLaNuit -> MatchsDeDemain();
        $homeplayers = $playerrepository -> findBy(['team'=>$teams[0]]);
        $awayplayers = $playerrepository -> findBy(['team'=>$teams[1]]);
        $odds = $MatchsDeLaNuit->CotesFaceAFace($hometeam,$awayteam);
        $image=$graphManager->player5Graph($homeplayers);
        $image2=$graphManager->player5Graph($awayplayers);
        $graph=[$image,$image2];
        $bet_error='';}
        else{return $this->redirectToRoute('game',['gameId' =>  $gameId]);}


        if(isset($_COOKIE['user']))
        {
            $userrepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userrepository->findOneBy(['username' => $_COOKIE['user']]);
            $bets = $user->getBets();
            $i=0;
            while($i<count($bets))
            {
                if ($bets[$i]->getGameId()==$gameId)
                {
                 $bet_error= "Vous avez déja enregistré un pari sur ce match!";
                } 
                $i++;   
            }
        }
        
        $form = $commentFormBuilder-> commentBuild($request,$gameId);
        $betForm = $betFormBuilder->betBuild($request);
        $comments = $commentRepository->findBy(['game' => $game]);
        $game = $repository->findOneBy(['gameId' => $gameId]);
        //$cotes = $StatsManager -> CotesFaceAFace($hometeam,$awayteam);
        return $this->render('face_a_face.html.twig', [
            'joueurs_domicile'=>$homeplayers,
            'joueurs_exterieur'=>$awayplayers,
            'matchs'=>$matchsDeLaNuit,
            'form'=> $form->createView(),
            'betForm'=> $betForm->createView(),
            'graph'=>$graph,
            'game'=>$game,
            'comments' => $comments,
            'teams'=>$teams,
            'tomorrow_matchs'=>$matchsDeDemain,
            'odds'=>$odds,
            'bet_error'=>$bet_error

        ]);
    }

    /**
     * @Route("/signin", name="signin")
     */
    public function signin(MatchsDeLaNuit $MatchsDeLaNuit,Request $request, SignInFormBuilder $signInFormBuilder)
    {
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit();   
        $matchsDeDemain = $MatchsDeLaNuit -> MatchsDeDemain();
        $form = $signInFormBuilder -> signInBuild($request);
        $error= "";
        if(isset($_COOKIE['user']))
        {
            setcookie('user',$_COOKIE['user']);
            return $this->redirectToRoute('main');
        }
        if(isset($_SESSION['error']))
        {
            $error = $_SESSION['error'];
        }
        return $this->render('signin.html.twig' ,[
            'matchs'=>$matchsDeLaNuit,
            'tomorrow_matchs'=>$matchsDeDemain,
            'form'=> $form->createView(),
            'error'=> $error

        ]);
    }

    /**
     * @Route("/signup", name="signup")
     */
    public function signup(MatchsDeLaNuit $MatchsDeLaNuit,Request $request, SignUpFormBuilder $signUpFormBuilder)
    {
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit(); 
        $matchsDeDemain = $MatchsDeLaNuit -> MatchsDeDemain();  
        $form = $signUpFormBuilder -> signUpBuild($request,$matchsDeLaNuit);
        $error= "";
        if(isset($_COOKIE['user']))
        {
            setcookie('user',$_COOKIE['user']);
            return $this->redirectToRoute('main');
        }
        if(isset($_SESSION['error']))
        {
            $error = $_SESSION['error'];
        }
        return $this->render('signup.html.twig' ,[
            'matchs'=>$matchsDeLaNuit,
            'tomorrow_matchs'=>$matchsDeDemain,
            'form'=> $form->createView(),
            'error'=> $error
        ]);
    }

    /**
     * @Route("/bet/{gameId}/{outcome}/{odd}", name="bet")
     */
    public function bet(MatchsDeLaNuit $MatchsDeLaNuit,int $gameId, int $outcome, string $odd)
    {
        $matchsDeLaNuit = $MatchsDeLaNuit -> MatchsDeLanuit(); 
        $matchsDeDemain = $MatchsDeLaNuit -> MatchsDeDemain();  
        if(isset($_COOKIE['user'])==false){
            return $this->redirectToRoute('signin');
        }
        $gamerepository = $this->getDoctrine()->getRepository(Game::class);
        $userrepository = $this->getDoctrine()->getRepository(User::class);
        $game = $gamerepository->findOneBy(['gameId' => $gameId]);
        $user = $userrepository->findOneBy(['username' => $_COOKIE['user']]);
        $entityManager = $this->getDoctrine()->getManager();
        $bet = new Bet();
        $bet->setGameId($gameId);
        $bet->setBet1N2($outcome);
        $bet->setOdd(+str_replace(',', '.', $odd));
        $bet->setStatus(0);
        $bet->setUserId($user);
        $bet->setDate(new \DateTime());
        $bets = $user->getBets();
        $i=0;
        while($i<count($bets))
        {
            if ($bets[$i]->getGameId()==$gameId)
            {
                return $this->redirectToRoute('face-a-face', ['gameId' =>  $gameId]);
            } 
            $i++;   
        }
        
        $user->addBet($bet);
        $entityManager->persist($game);
        $entityManager->persist($user);
        $entityManager->persist($bet);

        $entityManager->flush();
        return $this->redirectToRoute('main');
    }
    /**
     * @Route("/betsupdate", name="betsupdate")
     */
    public function checkBets(StatsManager $StatsManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $betRepository = $this->getDoctrine()->getRepository(Bet::class);
        $gameRepository = $this->getDoctrine()->getRepository(Game::class);
        $betsInProgress = $betRepository->findBy(['status' => 0]);
        foreach($betsInProgress as $bet)
        {
            $gameId=$bet->getGameId(); 
            $game=$gameRepository->findOneBy(['gameId' => $gameId]);
            $hteamid = $game->getHometeamid();
            $gameBets = $betRepository->findBy(['gameId' => $gameId]);
            foreach($gameBets as $bet){
                $result=null;
                $result=$StatsManager->returnWinOrLose($gameId,$hteamid);
                if($result==null)
                {
                    $bet->setStatus(0);
                }
                elseif($result=='W')     
                {
                    if($bet->getBet1N2()==1)
                    {
                        $bet->setStatus(1);
                    }
                    else{
                        $bet->setStatus(2);
                    }
                }
                elseif($result=='L')
                {
                    if($bet->getBet1N2()==1)
                    {
                        $bet->setStatus(2);
                    }
                    else{
                        $bet->setStatus(1);
                    }
                }
            }
            $entityManager->persist($bet);
            $entityManager->flush();
        }
dump($betsInProgress);die;    
    }
    
}