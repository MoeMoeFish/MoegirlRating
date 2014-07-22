<?php
header('Content-Type: application/json');

$vara = "I'm var";
$data = array( isSuccess => true, message => "Hello, World!", varable => $vara, integerV => 999 );

echo json_encode( $data );
