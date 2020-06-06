<?php
namespace App\Services;


class MatchsDeLaNuit 
{
    /**
 * Retourne la liste des matchs de la nuit NBA 
 *
 * @uses getAbvFromTeam()  
 * @uses http://www.elpauloloco.ovh/2020-03-06.json  (sauvegarde de nbastats du jour)
 *
 * 
 * @return array
 *     tableau avec noms et id des équipes à domicile et à l'exterieur ainsi que la date
 *     
**/
    public function MatchsDeLaNuit()
    {
        $response= $this->curlRequest('http://www.elpauloloco.ovh/2020-03-06.json');
        
        $matchsDeLaNuit=[];$match=[];
        
        for ($i=0; $i < count($response->resultSets[0]->rowSet) ; $i++)
        { 
            $homeTeamAbv= substr($response->resultSets[0]->rowSet[$i][5],9,3);
            $awayTeamAbv= substr($response->resultSets[0]->rowSet[$i][5],12,3);
            
            $match['HomeTeamId'] = $response->resultSets[0]->rowSet[$i][6]; 
            $match['HomeLogoUrl']= 'https://stats.nba.com/media/img/teams/logos/'.$homeTeamAbv.'_logo.svg';
            
            $match['AwayTeamId'] = $response->resultSets[0]->rowSet[$i][7];
            $match['AwayLogoUrl']= 'https://stats.nba.com/media/img/teams/logos/'.$awayTeamAbv.'_logo.svg';
            
            $match['GameId']= $response->resultSets[0]->rowSet[$i][2];
            $date = substr($response->resultSets[0]->rowSet[$i][4], 0, 7);
            $match['Time']=date('H:i', mktime(date('H', strtotime($date))+6,date('i', strtotime($date))));

            array_push($matchsDeLaNuit,$match);
        }
        return $matchsDeLaNuit;
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