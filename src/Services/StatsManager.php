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
        
      




           
        dump($matchsDeLaNuit);
        dump($response);die;
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