<?php
ini_set( 'display_errors', 'On' );
error_reporting( E_ALL );

try {
  $dbh = new PDO( 'mysql:host=127.0.0.1;port=3306;dbname=test;', 'moegirl', '456', array( PDO::ATTR_PERSISTENT => true ) );
  print 'Connecting OK <br />';
  
  $stmt = $dbh->prepare( 'SELECT * FROM rating_record WHERE wiki_id = ?' );
  print 'Prepare OK <br />';
  
  $name = '1';
  $stmt->bindParam( 1, $name );
  print 'Binding OK <br />';
  $stmt->execute();
  
  print 'Execute OK';
  
  $row = $stmt->fetch();
  
  print 'row count is ' . $stmt->rowCount();
} catch (Exception $ex) {
  print 'Error:' . $ex->getMessage() . '<br />';
}


