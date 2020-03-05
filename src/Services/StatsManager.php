<?php
namespace App\Services;

class StatsManager
{
    public function MatchsDeLaNuit()
    {
        $response= $this->curlRequest('http://www.elpauloloco.ovh/2020-03-06.json');
        $playerStats= $this->curlRequest('http://www.elpauloloco.ovh/playerstats.json');
        $matchsDeLaNuit=[];$match=[];
        
        for ($i=0; $i < count($response->resultSets[0]->rowSet) ; $i++)
        { 
            $match['HomeTeamId'] = $response->resultSets[0]->rowSet[$i][6] ;
            $homeTeamAbv=$this->getAbvFromId($match['HomeTeamId'],$playerStats);
            $match['HomeLogoUrl']= 'https://stats.nba.com/media/img/teams/logos/'.$homeTeamAbv.'_logo.svg';
            
            $match['AwayTeamId'] = $response->resultSets[0]->rowSet[$i][7];
            $awayTeamAbv=$this->getAbvFromId($match['AwayTeamId'],$playerStats);
            $match['AwayLogoUrl']= 'https://stats.nba.com/media/img/teams/logos/'.$awayTeamAbv.'_logo.svg';
            
            $match['GameId']= $response->resultSets[0]->rowSet[$i][7];
            $date = substr($response->resultSets[0]->rowSet[$i][4], 0, 7);
            $match['Time']=date('H:i', mktime(date('H', strtotime($date))+6,date('i', strtotime($date))));

            array_push($matchsDeLaNuit,$match);
        }
        return $matchsDeLaNuit;
    }

    public function teamStats($teamId)
    {
        $teams=$this->curlRequest('https://elpauloloco.ovh/teamstats.json');     
        $i=0;$teamstats=[];
        while($teamsstats->resultSets[0]->rowSet[$i][0] != $teamId){$i++;}
        $teamstats['Team']=$teamstats->resultSets[0]->rowSet[$i][1];
        $teamstats['TeamLogo']='https://stats.nba.com/media/img/teams/logos/'.$team.'_logo.svg';
        $teamstats['Wins'] = $teamsstats->resultSets[0]->rowSet[$i][3];
        $teamstats['Losses'] = $teamsstats->resultSets[0]->rowSet[$i][4];
        $teamstats['Points'] = $teamsstats->resultSets[0]->rowSet[$i][26];
        $teamstats['Possessions']= $teamsstats->resultSets[0]->rowSet[$i][4];
        $teamstats['Rebounds'] = $teamsstats->resultSets[0]->rowSet[$i][18];
        $teamstats['Assists'] = $teamsstats->resultSets[0]->rowSet[$i][19];
        $teamstats['BlockedShots'] = $teamsstats->resultSets[0]->rowSet[$i][22];
        $teamstats['Turnovers'] =$teamsstats->resultSets[0]->rowSet[$i][20];
        $teamstats['FieldGoalsPercentage'] = $teamsstats->resultSets[0]->rowSet[$i][9];
        return $teamstats;
    }   
      




    private function getAbvFromId($id,$playerStats)
    {
        $i=0;
        while ($playerStats->resultSets[0]->rowSet[$i][2] !=$id) {
            $i++;
        }
        return $playerStats->resultSets[0]->rowSet[$i][3];
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