<?php
/**
 * Small test script for TNSparser class.
 * @autor Sascha 'SieGeL' Pfalz <webmaster@saschapfalz.de>
 * @package TNSParser
 * @subpackage Testcase
 * @version 0.1 ($Id)
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */
/**
 */
require('./tnsparser.class.php');

$TNS = new tnsparser;
try
  {
  $retval = $TNS->ParseTNS('./oraexample6-4.ora');
  }
catch(Exception $e)
  {
  die(sprintf("%s\n",$e->getmessage()));
  }
foreach($retval AS $TNS => $TNSDATA)
  {
  printf("Found TNS entry \"%s\" on HOST=%s\n",$TNS,join(",",$TNSDATA['HOST']));
  }
#print_r($retval);
?>
