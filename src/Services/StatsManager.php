<?php
namespace App\Services;

use Symfony\Contracts\Translation\TranslatorInterface;


class StatsManager 
{
    public function CotesFaceAFace($homeId,$awayId)
    {
        $cotesParionsSport = $this->curlRequest('https://www.pointdevente.parionssport.fdj.fr/api/1n2/offre?sport=601600');
            for ($i=0; $i < count($cotesParionsSport); $i++) {
                if($cotesParionsSport[$i]->outcomes[0]->label==$this->getFrenchTeamNamebyId($homeId))
                {
                    $cote['Domicile'] = $cotesParionsSport[$i]->outcomes[0]->label;
                    $cote['Domicile_W'] = $cotesParionsSport[$i]->outcomes[0]->cote;
                    $cote['Exterieur']= $cotesParionsSport[$i]->outcomes[1]->label;
                    $cote['Exterieur_W']= $cotesParionsSport[$i]->outcomes[1]->cote;
                    return $cote;
                }
                elseif($cotesParionsSport[$i]->outcomes[0]->label==$this->getFrenchTeamNamebyId($awayId))
                {
                    $cote['Domicile'] = $cotesParionsSport[$i]->outcomes[1]->label;
                    $cote['Domicile_W'] = $cotesParionsSport[$i]->outcomes[1]->cote;
                    $cote['Exterieur']= $cotesParionsSport[$i]->outcomes[0]->label;
                    $cote['Exterieur_W']= $cotesParionsSport[$i]->outcomes[0]->cote;
                    return $cote;
                }
            }
        }
      

    /**
     * Renvoi un tableau des moyennes statistique pour l'équipe donné
     *
     * @uses returnStats()
     * @uses http://www.elpauloloco.ovh/TeamsStats.json  (sauvegarde des stats par équipe de nbastats )
     *
     * 
     * @param int $teamId
     * id de l'équipe (d'après nbastats)
     * @return array
     *     tableau avec stats des équipes à domicile et à l'exterieur ainsi que la date
     *     
    **/
    public function teamStats($teamId)
    {
        $teamsStats = $this->curlRequest('http://www.elpauloloco.ovh/TeamsStats.json');
        $defTeamsStats = $this->curlRequest('http://www.elpauloloco.ovh/DefTeamsStats.json');
        $teamStats = $this->returnStats($teamId,$teamsStats,$defTeamsStats);
     
        return $teamStats;
    } 
    
    public function teamStats5($teamId)
    {
        
        $teams5Stats = $this->curlRequest('http://www.elpauloloco.ovh/Last5TeamStats.json');
        $team5Stats = $this->returnStats($teamId,$teams5Stats,null);
        $teamStats= $this->teamStats($teamId);
        $team5Stats['pointsdiff']=$team5Stats['points']-$teamStats['points']; 
        $team5Stats['pointsRank']=  $team5Stats['pointsdiff'];
        $team5Stats['fg_pctRank']=$team5Stats['fg_pct']-$teamStats['fg_pct']; 
        $team5Stats['reboundsRank']=$team5Stats['rebounds']-$teamStats['rebounds']; 
        $team5Stats['blocksRank']=$team5Stats['blocks']-$teamStats['blocks']; 
        $team5Stats['assistsRank']=$team5Stats['assists']-$teamStats['assists']; 
        $team5Stats['stealsRank']=$team5Stats['steals']-$teamStats['steals']; 
        $team5Stats['turnoversRank']=$teamStats['turnovers']-$team5Stats['turnovers']; 
        return $team5Stats;
    }   

    public function locationStats($teamId, $location)
    {
        if ($location = 'Home')
        {
            $teamsStats = $this->curlRequest('http://www.elpauloloco.ovh/HomeTeamStats.json');
            $teamStats = $this->returnStats($teamId,$teamsStats,null);
            return $teamStats;
        }
        $teamsStats = $this->curlRequest('http://www.elpauloloco.ovh/RoadTeamStats.json');
        $teamStats = $this->returnStats($teamId,$teamsStats,null);
        return $teamStats;

    }

    public function advancedTeamStats($teamId)
    {
        
        $teamsStats = $this->curlRequest('http://www.elpauloloco.ovh/AdvancedTeamStats.json');
        $teamStats = $this->returnStats($teamId,$teamsStats,null);
        return $teamStats;
    }   

    public function players($teamId)
    {
        $players =  $this->curlRequest('http://www.elpauloloco.ovh/PlayersStats.json');
        $playersStats= $this->returnPlayerStats($players,$teamId);
        return $playersStats;
    }

    public function players5($teamId)
    {
        $players =  $this->curlRequest('http://www.elpauloloco.ovh/PlayersStats5.json');
        $playersStats= $this->returnPlayerStats($players,$teamId);
        return $playersStats;
    }

    public function returnPlayerStats($playersStats,$teamId)
    {
        $players=[];$player=[];
        for ($i=0; $i < count($playersStats->resultSets[0]->rowSet) ; $i++) { 
            if ($playersStats->resultSets[0]->rowSet[$i][2]==$teamId)
                {
                    $player['id']=$playersStats->resultSets[0]->rowSet[$i][0];
                    $player['name']=$playersStats->resultSets[0]->rowSet[$i][1];
                    $player['games']=$playersStats->resultSets[0]->rowSet[$i][5];
                    $player['minutes']=$playersStats->resultSets[0]->rowSet[$i][9];
                    $player['minutesRank']=$playersStats->resultSets[0]->rowSet[$i][38];

                    $player['points']=$playersStats->resultSets[0]->rowSet[$i][29];
                    $player['pointsRank']=$playersStats->resultSets[0]->rowSet[$i][58];
                    $player['rebounds']=$playersStats->resultSets[0]->rowSet[$i][21];
                    $player['reboundsRank']=$playersStats->resultSets[0]->rowSet[$i][50];
                    $player['assists']=$playersStats->resultSets[0]->rowSet[$i][22];
                    $player['assistsRank']=$playersStats->resultSets[0]->rowSet[$i][51];
                    $player['turnovers']=$playersStats->resultSets[0]->rowSet[$i][23];
                    $player['turnoversRank']=$playersStats->resultSets[0]->rowSet[$i][52];
                    $player['steals']=$playersStats->resultSets[0]->rowSet[$i][24];
                    $player['stealsRank']=$playersStats->resultSets[0]->rowSet[$i][53];
                    $player['blocks']=$playersStats->resultSets[0]->rowSet[$i][25];
                    $player['blocksRank']=$playersStats->resultSets[0]->rowSet[$i][54];
                    $player['plusminus']=$playersStats->resultSets[0]->rowSet[$i][30];
                    $player['plusminusRank']=$playersStats->resultSets[0]->rowSet[$i][59];
                    $player['fg_pct']=$playersStats->resultSets[0]->rowSet[$i][12];
                    $player['fg_pctRank']=$playersStats->resultSets[0]->rowSet[$i][41];
                    $player['three_fg_pct']=$playersStats->resultSets[0]->rowSet[$i][15];  
                    $player['three_fg_pctRank']=$playersStats->resultSets[0]->rowSet[$i][44];    
  




                    array_push($players,$player);
                }
        }
      
        usort($players, function($a, $b) {
            return $a['pointsRank'] <=> $b['pointsRank'];
        });
        return $players;
    }


    public function twitter($teamId){
        $team_abv=$this -> getAbvFromId($teamId);
        $leagueTwitters=[
            'CHI'=>'https://twitter.com/chicagobulls?s=20',
            'IND'=>'https://twitter.com/Pacers?s=20',
            'NOP'=>'https://twitter.com/PelicansNBA?s=20',
            'MIA'=>'https://twitter.com/MiamiHEAT?s=20',
            'ORL'=>'https://twitter.com/OrlandoMagic?s=20',
            'MIL'=>'https://twitter.com/Bucks?s=20',
            'MIN'=>'https://twitter.com/Timberwolves?s=20',
            'DAL'=>'https://twitter.com/dallasmavs?s=20',
            'LAL'=>'https://twitter.com/Lakers?s=20',
            'LAC'=>'https://twitter.com/laclippers',
            'CHA'=>'https://twitter.com/hornets?s=20',
            'WAS'=>'https://twitter.com/WashWizards?s=20',
            'OKC'=>'https://twitter.com/okcthunder?s=20',
            'NYK'=>'https://twitter.com/nyknicks?s=20',
            'DET'=>'https://twitter.com/DetroitPistons?s=20',
            'UTA'=>'https://twitter.com/utahjazz?s=20',
            'BOS'=>'https://twitter.com/celtics?s=20',
            'ATL'=>'https://twitter.com/ATLHawks?s=20',
            'SAS'=>'https://twitter.com/spurs?s=20',
            'PHI'=>'https://twitter.com/sixers?s=20',
            'BKN'=>'https://twitter.com/BrooklynNets?s=20',
            'CLE'=>'https://twitter.com/cavs?s=20',
            'TOR'=>'https://twitter.com/Raptors?s=20',
            'MEM'=>'https://twitter.com/memgrizz?s=20',
            'POR'=>'https://twitter.com/trailblazers?s=20',
            'PHX'=>'https://twitter.com/Suns?s=20',
            'GSW'=>'https://twitter.com/warriors?s=20',
            'SAC'=>'https://twitter.com/SacramentoKings?s=20',
            'HOU'=>'https://twitter.com/HoustonRockets?s=20',
            'DEN'=>'https://twitter.com/nuggets?s=20'
        ];
        $twitter=$leagueTwitters[$team_abv];
        return $twitter;
    }
     // Injuries : 
    public function injury($teamId){
        $injuredPlayers = $this->curlRequest('https://www.rotowire.com/basketball/tables/injury-report.php?team=ALL&pos=ALL');
        $team_abv=$this -> getAbvFromId($teamId);
        $infirmerie=[];$injury= []; 
        for ($i=0; $i < count($injuredPlayers); $i++) { 
            if($injuredPlayers[$i]->team ==$team_abv){
                    $injury['player']=$injuredPlayers[$i]->player;
                    $injury['injury']=$injuredPlayers[$i]->injury;
                    $injury['status']=$injuredPlayers[$i]->status;
                    array_push($infirmerie,$injury);
            }
        }
        return $infirmerie;
    }




    public function returnStats($teamId,$teamsStats,$defTeamsStats)
    {
        
        $i=0;
        while($teamsStats->resultSets[0]->rowSet[$i][0] !=$teamId){
            $i++;}
        $stats['Team']=$teamsStats->resultSets[0]->rowSet[$i][1];    
        $stats['team_abv'] = $this -> getAbvFromId($teamId);       
        $stats['points']=$teamsStats->resultSets[0]->rowSet[$i][26];
        $stats['pointsRank'] = $teamsStats->resultSets[0]->rowSet[$i][52];
        $stats['rebounds']=$teamsStats->resultSets[0]->rowSet[$i][18];
        $stats['reboundsRank'] = $teamsStats->resultSets[0]->rowSet[$i][44];
        $stats['assists']=$teamsStats->resultSets[0]->rowSet[$i][19];
        $stats['assistsRank'] = $teamsStats->resultSets[0]->rowSet[$i][45];
        $stats['blocks']=$teamsStats->resultSets[0]->rowSet[$i][22];
        $stats['blocksRank'] = $teamsStats->resultSets[0]->rowSet[$i][48];
        $stats['steals']=$teamsStats->resultSets[0]->rowSet[$i][21];
        $stats['stealsRank'] = $teamsStats->resultSets[0]->rowSet[$i][47];
        $stats['fg_pct']=$teamsStats->resultSets[0]->rowSet[$i][9];
        $stats['fg_pctRank'] = $teamsStats->resultSets[0]->rowSet[$i][35];
        if (isset($defTeamsStats)){
            $j=0;while($defTeamsStats->resultSets[0]->rowSet[$j][0] !=$teamId){$j++;}
            $stats['d_fg_pct']=$defTeamsStats->resultSets[0]->rowSet[$j][8];
            $rank=1;
            for ($k=0; $k < count($defTeamsStats->resultSets[0]->rowSet); $k++){
                if ($defTeamsStats->resultSets[0]->rowSet[$k][8] <= $stats['d_fg_pct']) {
                    $rank++; }}
            $stats['d_fg_pctRank']=$rank;
        }
        $stats['turnovers']=$teamsStats->resultSets[0]->rowSet[$i][20];
        $stats['turnoversRank'] = $teamsStats->resultSets[0]->rowSet[$i][46];
        $stats['wins'] =  $teamsStats->resultSets[0]->rowSet[$i][3];
        $stats['losses'] = $teamsStats->resultSets[0]->rowSet[$i][4];
        $stats['Rank'] = $teamsStats->resultSets[0]->rowSet[$i][31];

        return $stats;
    }


   




    private function getFrenchTeamNamebyId($id)
    {
        $teamsStats= $this->curlRequest('http://www.elpauloloco.ovh/teamstats.json');
        $i=0;
        while ($teamsStats->resultSets[0]->rowSet[$i][0] !=$id) {$i++;}
        $string=$teamsStats->resultSets[0]->rowSet[$i][1];
        $city = explode(' ', $string);
        $removed = array_pop($city);
        if(implode(' ',$city)=='Los Angeles'){
                return 'LA Lakers';}
        elseif($city[0]=='LA'){
            $city= 'LA Clippers';}
        elseif ($city[0]=='Philadelphia') {
            $city = 'Philadelphie';}
        return implode(' ',$city);
    }

    private function getAbvFromId($id)
    {
        $playerStats= $this->curlRequest('http://www.elpauloloco.ovh/playerstats.json');
        $i=0;
        while ($playerStats->resultSets[0]->rowSet[$i][2] !=$id) {
            $i++;
        }
        return $playerStats->resultSets[0]->rowSet[$i][3];
    }

    public function last5games($id)
    {
        $games= $this->curlRequest('http://www.elpauloloco.ovh/teamLastGames.json');
        $i=0;$j=0;$last5=[];
        while($i<5)
        {
            if($games->resultSets[0]->rowSet[$j][1]==$id)
            {
                $game=$games->resultSets[0]->rowSet[$j];
                $i++;
                array_push($last5,$game);
            }
            $j++;
        }
        return $last5;
    }
    public function playerLast5games($id,$playerId)
    {   
        $games= $this->curlRequest('http://www.elpauloloco.ovh/playerLastGames.json');
        $i=0;$j=0;$last5=[];
        while($i<5 && $j<count($games->resultSets[0]->rowSet))
        {
        if($games->resultSets[0]->rowSet[$j][3]==$id )
                {   
                    if($games->resultSets[0]->rowSet[$j][1]==$playerId)
                    {
                    $game=$games->resultSets[0]->rowSet[$j];
                    $i++;
                    array_push($last5,$game);
                    }
                }
                $j++;
            }
        return $last5;
    }


    private function curlRequest($url)
    {
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_URL, $url);   
    
     $result = curl_exec($ch);
     curl_close($ch);
     $data = json_decode($result);
     return $data;
    }
}