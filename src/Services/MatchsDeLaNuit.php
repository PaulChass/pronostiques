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
        $date=time()-(6*60*60);
        $response= $this->curlRequest('http://www.elpauloloco.ovh/'.date('Y-m-d',$date).'.json');
        $responselive = $this->curlRequest('https://stats.nba.com/js/data/pointsbet/2019/'.date('Ymd',$date).'.json');
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
            $gid=$match['GameId'];
            $date = substr($response->resultSets[0]->rowSet[$i][4], 0, 7);
            if(substr($date, -1)=='p')
            {
                $date=$date.'m';
            }
            if(isset($responselive->$gid->timeRemaining) && $responselive->$gid->timeRemaining!==null)
            {
                $match['period']= $responselive->$gid->period;
                $match['timeRemaining']= $responselive->$gid->timeRemaining;
                $match['homeScore']= $responselive->$gid->homeScore;
                $match['awayScore']= $responselive->$gid->awayScore;
                $match['id']=$i+1;
                
            }
            else{
                $match['period']= null;
                $match['timeRemaining']= null;
                $match['homeScore']= null;
                $match['awayScore']= null;
                $match['id']=null;

            }
            $match['Time']=date('H:i', mktime(date('H', strtotime($date))+6,date('i', strtotime($date))));

            array_push($matchsDeLaNuit,$match);
        }
        return $matchsDeLaNuit;
    }
    private function getFrenchTeamNamebyId($id)
    {
        $teamsStats= $this->curlRequest('http://www.elpauloloco.ovh/TeamsStats.json');
        $i=0;
        while ($teamsStats->resultSets[0]->rowSet[$i][0] !=$id) {$i++;}
        $string=$teamsStats->resultSets[0]->rowSet[$i][1];
        $city = explode(' ', $string);
        $removed = array_pop($city);
        if(implode(' ',$city)=='Los Angeles'){
                return 'LA Lakers';}
        elseif($city[0]=='LA'){
            return $city= 'LA Clippers';}
        elseif ($city[0]=='Philadelphia') {
            return $city = 'Philadelphie';}
        elseif($city[0]=='Portland'){
            return $city='Portland';
        }
        if (isset($city[1])){return implode(' ',$city);}
        return $city[0];
    }

    public function MatchsDeDemain()
    {
        $today=time()-(6*60*60);
        $tomorrow= $today + (24*60*60);
        $response= $this->curlRequest('http://www.elpauloloco.ovh/'.date('Y-m-d',$tomorrow).'.json');
        
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
            if(substr($date, -1)=='p')
            {
                $date=$date.'m';
            }
            $match['Time']=date('H:i', mktime(date('H', strtotime($date))+6,date('i', strtotime($date))));
            array_push($matchsDeLaNuit,$match);
        }
        return $matchsDeLaNuit;
    }
    public function CotesFaceAFace($homeId,$awayId)
    {
        $cotesParionsSport = $this->curlRequest('https://www.pointdevente.parionssport.fdj.fr/api/1n2/offre?sport=601600');
        for ($i=0; $i < count($cotesParionsSport); $i++) {
                if($cotesParionsSport[$i]->label==$this->getFrenchTeamNamebyId($homeId).'-'.$this->getFrenchTeamNamebyId($awayId))
                {
                   $cote['1'] = $cotesParionsSport[$i]->outcomes[0]->cote;
                    $cote['2']= $cotesParionsSport[$i]->outcomes[2]->cote;
                   
                    return $cote;
                }
            }
        }
    private function curl_get_contents($url)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
    public function getlink($hname,$aname)
    {   
        $DEVELOPER_KEY = 'AIzaSyDHKLxOrQ4xFSHPRk4AQwIvp-p-8D3qEAk';

        $client = new \Google_Client();
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.force-ssl',
        ]);
        $client->setDeveloperKey($DEVELOPER_KEY);

        // Define service object for making API requests.
        $youtube = new \Google_Service_YouTube($client);

        $response = $youtube->search->listSearch('id,snippet', array(
        'q' => $hname.'VS'.$aname.'Full+Game+Recap',
        'maxResults' => 5,
        ));
        $link="";
        if(isset($response['items'][0])){
            $link= $response['items'][0]['id']['videoId'];
        }    
        return($link);
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