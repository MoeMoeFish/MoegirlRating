<html>
<head>
  <title>Rating</title>
  <script src="javascripts/jquery-1.11.1.js" type="text/javascript" ></script>
  <script src="javascripts/moegirl_rating.js" type="text/javascript" ></script>
  <link rel="stylesheet" type="text/css" href="stylesheets/moegirl_rating.css" >



</head>
<body>
<div id="rating-main" style="margin-top: 200px; width: 600px; margin-left: auto; margin-right: auto;">
  <div class="moegirl_rating">
    <div class="rating_title">Wiki质量</div>
    <div class="rating_body_disabled rating_main" >
      <ul>
        <li><a class="r-1" >1</a></li>
        <li><a class="r-2" >2</a></li>
        <li><a class="r-3" >3</a></li>
        <li><a class="r-4" >4</a></li>
        <li><a class="r-5" >5</a></li>
      </ul>

      <div class="rating_body_result" ></div>
    </div>
    <div class="rating_result" ><div class="result_icon loading" ></div><div class="result_text" ></div></div>
  </div>

  <script type="text/javascript" >
    new MoegirlRatingControl("#rating-main").init();
  </script>
</div>

</body>
