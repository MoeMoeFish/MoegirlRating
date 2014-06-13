$(document).ready(function() {
  var wikiId = "moegirl";
  var isDuplicated = false;
  var hasRating = false;
  var ratingScore = 0;

  
  setTimeout(function() {
    $.ajax({
      url: "",
      type: "GET",
      data: { 'wikiid': wikiId},
      success: function(data) {
        // @ForTest
        {
          data = {};
          data.isDuplicated = false;
          data.hasRating = false;
          data.score = 3.8;
          data.resultHtml = "<strong>7</strong> 人打分，平均分 <strong>3.8</strong> 分";
        }

        initialRating( data );
        $( ".rating_body li a" ).click( function( event ) {
          ratingClick( event );
        });
      }
    }).fail(function() {
     $( ".rating_result" ).removeClass( "loading" ).text( "错误，无法加载打分结果" );
    });
  }, 1000);

  function ratingClick( event ) {
    ratingScore = new Number($(event.target).text());
    ratingScore = correctScore(ratingScore);

    if ( hasRating || isDuplicated ) {
      alert( "你已经打过分了" );
    } else {
      $( ".rating_main" ).addClass( "rating_body_disabled" ).removeClass( "rating_body" );
      $( ".rating_body_result" ).width(0);

      $(".rating_result").addClass("loading").html("");

      setTimeout(function () {
      $.ajax({
        url: "",
        type: "GET",
        data: { "score" : ratingScore },
        success: function( data ) {
          // @ForTest
          {
            data = {};
            data.hasRating = true;
            data.isDuplicated = false;
            data.score = 4.5;

            if (data.isDuplicated) {
              data.resultHtml = "你已经打过分了";
            } else {
              var newScore =  ( 7 * 3.8 + ratingScore ) / 8
              data.resultHtml = "<strong>8</strong> 人打分，平均分 <strong>" + newScore.toFixed(1)  + "</strong> 分";
            }
          }
  
          initialRating(data);
        }
      }).error(function() { 
        $( ".rating_result" ).removeClass( "loading" ).text( "错误，无法加载打分结果" );
      });
      }, 1000);
    }
  }

  function initialRating( data ) {
    isDuplicated = data.isDuplicated;
    hasRating = data.hasRating;

    $(".rating_result").removeClass("loading").html(data.resultHtml);

    if ( !data.hasRating && !data.isDuplicated ) {
      $( ".rating_main" ).addClass( "rating_body" ).removeClass( "rating_body_disabled" );
    } else {
      $( ".rating_main" ).addClass( "rating_body_disabled" ).removeClass( "rating_body" );
    }

    var score = correctScore(data.score);
    $( ".rating_body_result" ).width( ( score / 5 ) * 150 );
  }


  function correctScore(score) {
    if (score < 0) {
      return 0;
    } else if (score > 5) {
      return 5;
    } else {
      return score;
    }
  }
    
});


