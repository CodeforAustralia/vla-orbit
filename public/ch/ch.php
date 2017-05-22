<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
require_once('../../simplesaml/lib/_autoload.php');


$as = new SimpleSAML_Auth_Simple('ORBIT');
$as->requireAuth();
var_dump($as);

$attributes = $as->getAttributes();
print_r($attributes);