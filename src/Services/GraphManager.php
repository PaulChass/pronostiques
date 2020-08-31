<?php
namespace App\Services;

use Amenadiel\JpGraph\Graph\Graph;
use Amenadiel\JpGraph\Plot\ScatterPlot;
use Amenadiel\JpGraph\Plot\LinePlot;

use Amenadiel\JpGraph\Util\Spline;


class GraphManager
{
    public function player5Graph($players)
    {
      $datay=[];$pointsoverlast5=[];$name=[];$team=[];
      for ($i=0; $i < 5; $i++) { 
          $last5 = $players[$i]->getLast5Games();
          array_push($name,$last5[$i][2]);
          for($j=0;$j<5;$j++)
          {
              $pointsoverlast5[4-$j]=$last5[$j][28];
              $team[4-$j]=substr($last5[$j][8],4); 
          }
        array_push($datay,$pointsoverlast5);
        $pointsoverlast5=[];
}
// Setup the graph
$graph = new Graph(480,360);
$graph->SetMarginColor('white');
$graph->SetScale("textlin");
$graph->title->Set('Points sur les 5 derniers matchs');
$graph->title->SetColor('navy');
$graph->SetFrame(false);
$graph->SetMargin(30,5,25,5);
 
// Enable X-grid as well
$graph->xgrid->Show();

// Use months as X-labels
$graph->xaxis->SetTickLabels($team);
 
//------------------------
// Create the plots
//------------------------
$p1 = new LinePlot($datay[0]);
$p1->SetColor("navy");
 
// Use a flag
//$p1->mark->SetType(MARK_FLAG1,197);
 
// Displayes value on top of marker image
$p1->value->SetFormat('');
$p1->value->Show();
$p1->value->SetColor('navy');
$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
// Increase the margin so that the value is printed avove tje
// img marker
$p1->value->SetMargin(14);
 
// Incent the X-scale so the first and last point doesn't
// fall on the edges
$p1->SetCenter();
  
// Set the legends for the plots
$p1->SetLegend($name[0]);
$graph->Add($p1);
 
//------------
// 2:nd plot
//------------
$p2 = new LinePlot($datay[1]);
$p2->SetColor("brown");
 
// Use a flag
//$p2->mark->SetType(MARK_FLAG1,'united states');
 
// Displayes value on top of marker image
$p2->value->SetFormat('');
$p2->value->Show();
$p2->value->SetColor('brown');
$p2->value->SetFont(FF_ARIAL,FS_BOLD,10);
// Increase the margin so that the value is printed avove tje
// img marker
$p2->value->SetMargin(14);
 
// Incent the X-scale so the first and last point doesn't
// fall on the edges
$p2->SetCenter();
$p2->SetLegend($name[1]);

$graph->Add($p2);


// 3:nd plot
//------------
$p3 = new LinePlot($datay[2]);
$p3->SetColor("green");
 
// Use a flag
//$p3->mark->SetType(MARK_FLAG1,'united states');
 
// Displayes value on top of marker image
$p3->value->SetFormat('');
$p3->value->Show();
$p3->value->SetColor('brown');
$p3->value->SetFont(FF_ARIAL,FS_BOLD,10);
// Increase the margin so that the value is printed avove tje
// img marker
$p3->value->SetMargin(14);
 
// Incent the X-scale so the first and last point doesn't
// fall on the edges
$p3->SetCenter();
$p3->SetLegend($name[2]);

$graph->Add($p3);

 
// Adjust the legend position
$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.5,0.95,"center","bottom");
$graph->SetBackgroundCFlag('united states') ;       
// Display the graph
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
//Start buffering
ob_start();      
//Print the data stream to the buffer
$graph->img->Stream(); 
//Get the conents of the buffer
$image_data = ob_get_contents();
//Stop the buffer/clear it.
ob_end_clean();
//Set the variable equal to the base 64 encoded value of the stream.
//This gets passed to the browser and displayed.
$image = base64_encode($image_data);
return $image;     
    }
}