<?php
namespace App\Services;

class StatsManager
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
            $match['HomeTeamId'] = $response->resultSets[0]->rowSet[$i][6] ;
            $homeTeamAbv=$this->getAbvFromId($match['HomeTeamId']);
            $match['HomeLogoUrl']= 'https://stats.nba.com/media/img/teams/logos/'.$homeTeamAbv.'_logo.svg';
            
            $match['AwayTeamId'] = $response->resultSets[0]->rowSet[$i][7];
            $awayTeamAbv=$this->getAbvFromId($match['AwayTeamId']);
            $match['AwayLogoUrl']= 'https://stats.nba.com/media/img/teams/logos/'.$awayTeamAbv.'_logo.svg';
            
            $match['GameId']= $response->resultSets[0]->rowSet[$i][7];
            $date = substr($response->resultSets[0]->rowSet[$i][4], 0, 7);
            $match['Time']=date('H:i', mktime(date('H', strtotime($date))+6,date('i', strtotime($date))));

            array_push($matchsDeLaNuit,$match);
        }
        return $matchsDeLaNuit;
    }

    /**
     * Renvoi un tableau des moyennes statistique pour l'équipe donné
     *
     * @uses getAbvFromTeam()  
     * @uses https://stats.nba.com/media/img/teams/logos/
     * @uses https://elpauloloco.ovh/teamstats.json  (sauvegarde des stats par équipe de nbastats )
     *
     * 
     * @param int $teamId
     * id de l'équipe (d'après nbastats)
     * @return array
     *     tableau avec noms et id des équipes à domicile et à l'exterieur ainsi que la date
     *     
    **/
    public function StatsEquipe($teamId)
    {
        $teamsstats=$this->curlRequest('https://elpauloloco.ovh/teamstats.json');     
        $i=0;$teamstats=[];
        while($teamsstats->resultSets[0]->rowSet[$i][0] != $teamId){$i++;}
        $teamstats['Team']=$teamsstats->resultSets[0]->rowSet[$i][1];
        $teamstats['TeamLogo']='https://stats.nba.com/media/img/teams/logos/'.$this->getAbvFromId($teamId).'_logo.svg';
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

    /**
 * \file main.c
 * \brief Programme de tests.
 * \author Franck.H
 * \version 0.1
 * \date 11 septembre 2007
 *
 * Programme de test pour l'objet de gestion des chaînes de caractères Str_t.
 *
 */
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