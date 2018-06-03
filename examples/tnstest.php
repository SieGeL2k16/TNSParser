<?php
/**
 * Small test script for TNSparser class.
 * @autor Sascha 'SieGeL' Pfalz <php@saschapfalz.de>
 * @package TNSParser\Testcase
 * @version 1.0.0
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */
/**
 */
require('./../tnsparser.class.php');

$tns_files = glob("*.ora");

$TNS = new spfalz\TNSparser();
foreach($tns_files AS $tns)
  {
  printf("Reading File: %s\n",$tns);
  try
    {
    $tdata = $TNS->ParseTNS($tns);
    }
  catch(Exception $e)
    {
    printf("ERROR reading file %s: %s\n",$tns,$e->getMessage());
    continue;
    }
  foreach($tdata AS $tname => $TNSDATA)
    {
    if(isset($TNSDATA['HOST']) == FALSE)
      {
      printf("Found TNS entry \"%s\" with PROTOCOL=%s\n",$tname,join(",",$TNSDATA['PROTOCOL']));
      }
    else
      {
      printf("Found TNS entry \"%s\" on HOST=%s\n",$tname,join(",",$TNSDATA['HOST']));
      }
    }
  }
