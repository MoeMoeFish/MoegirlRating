<?php
$mysqli = new mysqli( '127.0.0.1', 'moegirl', '456', 'test' );

$res = $mysqli->query('SELECT * FROM rating_record WHERE wiki_id = 1');
echo $res->num_rows; 
echo "\n";

$stmt = $mysqli->prepare('SELECT * FROM rating_record WHERE wiki_id = 1');
$stmt->bind_param('i', 1);
if (!$stmt->execute()) {
  echo 'execute error' . "\n";
}
if (!($restult = $stmt->get_result())) {
  echo 'Getting reuslt error: ' . $stmt->errno . " " . $stmt->error;
}
echo $restult->num_rows;
