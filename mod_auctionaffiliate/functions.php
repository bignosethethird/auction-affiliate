<?php
/*
* @version    $Id: functions.php 49 2013-11-17 10:58:00Z gerrit_hoekstra $
* @package    Joomla
* @copyright  Copyright (C) 2012 www.hoekstra.co.uk. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
  defined('_JEXEC') or die('Restricted access');

  // Check if cURL is installed
  function IscURLInstalled() {
    return (in_array('curl', get_loaded_extensions()))? true:false;
  }
  // Can we use file_get_contents() ?
  function IsFileGetContentsOK(){
      return (ini_get("allow_url_fopen") || ini_get("allow_url_include"))?1:0;
  }

  // Define curl header once-off:
  if (!defined("ch")){
    if(IscURLInstalled()){
      function setupch(){
        $ch = curl_init();
        $c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return($ch);
      }
      define("ch", setupch());
      // Replaces file_get_contents as a partial solution, which is necessary if
      // the PHP config has been set to:
      //   allow_url_fopen=off
      //   allow_url_include=off
      // ... and you can't change it on the server.
      function curl_get_contents($url){
        $c = curl_setopt(ch, CURLOPT_URL, $url);
        return(curl_exec(ch));
      }
    }
  }
?>


