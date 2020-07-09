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

  $(".playerstats").hide();
  $("#player").show();
  $(".btn-player").click(function(){
    $(".playerstats").hide();
    $("#player").show();
  });
  $(".btn-player5").click(function(){
    $(".playerstats").hide();
    $("#player5").show();
  });


  $(".left").hover(function(){
    $(".hovershow").hide();
    console.log('What up dough! ');
  });
$("#ts").hover(function(){
  $(".hovershow").show();
  console.log('What up dough! ');
});

$('span.hovershow').hide();







    if ($('.ty-compact-list1').length > 3) {
      $('.ty-compact-list1:gt(5)').hide();
      $('.show-more1').show();
    }
    
    $('.show-more1').on('click', function() {
      //toggle elements with class .ty-compact-list that their index is bigger than 2
      $('.ty-compact-list1:gt(5)').toggle();
      //change text of show more element just for demonstration purposes to this demo
      $(this).text() === 'Voir plus' ? $(this).html('<td>Voir moins</td>') : $(this).html('<td>Voir plus</td>');
    });
  


    if ($('.ty-compact-list2').length > 3) {
      $('.ty-compact-list2:gt(5)').hide();
      $('.show-more2').show();
    }
    
    $('.show-more2').on('click', function() {
      //toggle elements with class .ty-compact-list that their index is bigger than 2
      $('.ty-compact-list2:gt(5)').toggle();
      //change text of show more element just for demonstration purposes to this demo
      $(this).text() === 'Voir plus' ? $(this).html('<td>Voir moins</td>') : $(this).html('<td>Voir plus</td>');
    });


    if ($('.ty-compact-list3').length > 3) {
      $('.ty-compact-list3:gt(5)').hide();
      $('.show-more3').show();
    }
    
    $('.show-more3').on('click', function() {
      //toggle elements with class .ty-compact-list that their index is bigger than 2
      $('.ty-compact-list3:gt(5)').toggle();
      //change text of show more element just for demonstration purposes to this demo
      $(this).text() === 'Voir plus' ? $(this).html('<td>Voir moins</td>') : $(this).html('<td>Voir plus</td>');
    });

    if ($('.ty-compact-list4').length > 3) {
      $('.ty-compact-list4:gt(5)').hide();
      $('.show-more4').show();
    }
    
    $('.show-more4').on('click', function() {
      //toggle elements with class .ty-compact-list that their index is bigger than 2
      $('.ty-compact-list4:gt(5)').toggle();
      //change text of show more element just for demonstration purposes to this demo
      $(this).text() === 'Voir plus' ? $(this).html('<td>Voir moins</td>') : $(this).html('<td>Voir plus</td>');
    });




 //// BOUTONS VOIR PLUS 
//this will execute on page load(to be more specific when document ready event occurs)















    
console.log('Hellooo Webpack Ence! ');
