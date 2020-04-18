/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

$(".stats").hide();$("#overall").show();
$(".btn-overall").click(function(){
  $(".stats").hide();
  $("#overall").show();
});

  $(".btn-last5").click(function(){
    $(".stats").hide();
    $("#last5").show();
  });

  $(".btn-location").click(function(){
    $(".stats").hide();
    $("#location").show();
  });

  $(".btn-advanced").click(function(){
    $("#overall").hide();
    $("#last5").hide();
    $("#location").hide();
    $("#advanced").show();
  });

  $(".btn-players").click(function(){
    $(".stats").hide();
    $("#players").show();
  });
  $(".btn-injuries").click(function(){
    $(".stats").hide();
    $("#injuries").show();
  });
  
  

    
console.log('Hellooo Webpack Ence! ');
