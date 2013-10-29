<?php
/**
 * Class to Parse tnsnames.ora file.
 * @author Sascha 'SieGeL' Pfalz <webmaster@saschapfalz.de>
 * @version 0.1 ($Id)
 * @license http://opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * @package TNSparser
 */
class TNSparser
  {
  /** Version of this class */
  const CLASS_VERSION  = '0.1';

  /**
   * Parses a given TNS file and returns the contents of that file as associative array.
   * In case of an error throws an exception.
   * @param string $tfile The complete filename to the tnsnames.ora file.
   * @return array The contents of the file as associative array
   */
  public function ParseTNS($tfile)
    {
    $openCount  = $closeCount = 0;
    $tnsEntries = array();

    if(@file_exists($tfile) === FALSE)
      {
      throw new Exception(sprintf('Passed file "%s" not found / not readable!',$tfile));
      }
    if(!($fp = fopen($tfile,"rb")))
      {
      throw new Exception(sprintf('Unable to open file "%s" for reading??',$tfile));
      }
    $lineCount  = 0;
    $lineBuffer = '';
    while(feof($fp) !== TRUE)
      {
      $line = trim(fgets($fp,4096));
      // Skip empty and comment lines
      if($line == '' || $line[0] == '#')
        {
        continue;
        }
      // Remove line-wise comments
      $line = trim(preg_replace("/(.*)(\#.*)/","$1",$line));
      // Read in line-by-line, analysing in memory.
      for($i = 0; $i < strlen($line); $i++)
        {
        if($line[$i] == '(')
          {
          $openCount++;
          }
        if($line[$i] == ')')
          {
          $closeCount++;
          }
        }
      // Merge data without any line feeds
      $lineBuffer.=$line;
      // If close == open one TNS entry is complete
      if($openCount && $openCount == $closeCount)
        {
        $tnsEntries[$tnsname] = $this->ParseTNSEntry($lineBuffer,$tnsname);
        $lineBuffer = '';
        $openCount = $closeCount = 0;
        }
      $lineCount++;
      }
    fclose($fp);
    if($openCount != $closeCount)
      {
      throw new Exception(sprintf('TNS file "%s" seems to be malformed - please check syntax!',$tfile));
      }
    ksort($tnsEntries);
    return($tnsEntries);
    } // ParseTNS()

  /**
   * Takes a complete TNS string and returns an array with all known components added as key/value pairs.
   * @param string $tnsentry One complete TNS entry.
   * @param string &$tnsname The parsed tns name.
   * @return array The components of the TNS entry splitted.
   */
  private function ParseTNSEntry($tnsentry,&$tnsname)
    {
    $isOpen = $isClose = 0;
    // Retrieve TNS name first, we use this as index for the array
    $tnsname = trim(preg_replace("/^(.*?)(=)(.*)/","$1",$tnsentry));
    $tnsdata = trim(preg_replace("/^(.*?)(=)(.*)/","$3",$tnsentry));
    for($i = 0; $i < strlen($tnsdata); $i++)
      {
      if($tnsdata[$i] == '(')
        {
        $isOpen++;
        $cmdarray[$isOpen] = '';
        }
      if($tnsdata[$i] == ')')
        {
        $isClose++;
        }
      $cmdarray[$isOpen].=$tnsdata[$i];
      }
    $retarray = array();
    foreach($cmdarray AS $i => $idata)
      {
      if(isset($cmdarray[$i]) === FALSE) continue;
      $cdata = explode("=",str_replace(array("(",")"),"",$cmdarray[$i]));
      if(isset($cdata[1]) === TRUE && trim($cdata[1]) != '')
        {
        $retarray[StrToUpper(trim($cdata[0]))][] = trim($cdata[1]);
        }
      }
    return($retarray);
    }

  } // End-of-class
?>
