<?php
/*
* @version    $Id: helper.php 50 2013-11-17 15:00:32Z gerrit_hoekstra $
* @package    Joomla
* @copyright  Copyright (C) 2010 www.hoekstra.co.uk. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once("functions.php");

class mod_auctionaffiliate {
  public static function getAuctionAffiliate(&$params) {
    global $mainframe;

    $debug                = $params->get('debug','0');
    $apiid                = $params->get('apiid','');
    $responseEncoding     =($params->get('encoding', '0')==1)?"XML":"JSON";
    $itemid               = $params->get('itemid', '');
    $SafeItemID           = urlencode($itemid);
    $sourceSite           = $params->get('sourceSite', '0');
    $affiliate_enable     = $params->get('affiliate_enable', '0');
    $affiliate_trackingid = $params->get('affiliate_trackingid', '0');
    $affiliate_partner    = $params->get('affiliate_partner', '0');
    if($params->get('sandbox_enable', '0')==1){
      $endpoint   = "http://open.api.sandbox.ebay.com/shopping";  // URL to call if you want to test against debug sandbox
    }else{
      $endpoint   = "http://open.api.ebay.com/shopping";  // URL for standard usage
    }

    // Check that necessary parameters are set up before proceeding
    if(!$SafeItemID || strlen($SafeItemID)!=12){
      JError::raiseWarning(100,JTEXT::sprintf('ERROR_ITEM_ID',JTEXT::_('MODULE_NAME')),JTEXT::_('ERROR_ITEM_ID_INFO'));
      return;
    }
    if($affiliate_enable && (!$affiliate_trackingid || !$affiliate_partner)){
      JError::raiseWarning(101,JTEXT::sprintf('ERROR_AFFILIATE_ID',JTEXT::_('MODULE_NAME')),JTEXT::_('ERROR_AFFILIATE_ID_INFO'));
      return;
    }

    $apicall = "$endpoint?callname=GetSingleItem"
     . "&version=725"
     . "&responseencoding=$responseEncoding"
     . "&appid=$apiid"
     . "&siteid=$sourceSite"
     . "&ItemID=$SafeItemID";
    if($debug){
      $apicall .= "&IncludeSelector=Details,Description";
    }
    if($affiliate_enable){
      $apicall .= "&trackingid=$affiliate_trackingid"."&trackingpartnercode=$affiliate_partner";  // URL will be http://rover.ebay.com/rover/.. & not http://cgi.ebay.com/...
    }

    if($debug){
      echo "<strong>API Call to eBay:</strong><br/>";
      echo $apicall."<br/>";
      echo "<strong>PHP Configuration:</strong><br/>";
      echo "file_get_contents".((IsFileGetContentsOK())?" is ":" is not ")."supported.<br/n>";
      echo "cURL library".((IscURLInstalled())?" is ":" is not ")."installed.<br/n>";
    }

    // Load the call and capture the document returned by eBay API
    // If allow_url_fopen=off or allow_url_include=off use curl instead:
    if(IsFileGetContentsOK()){
      if ($responseEncoding=='JSON'){
        // New style JSON
        $resp = json_decode(file_get_contents($apicall));
      } else {
        // Old-style XML
        $resp = simplexml_load_file($apicall);
      }
    }else{
      if(IscURLInstalled()){
        if ($responseEncoding=='JSON'){
          $resp = json_decode(curl_get_contents($apicall));
        }else{
          $resp = simplexml_load_string(curl_get_contents($apicall));
        }
      }else{
        JError::raiseWarning(103,JTEXT::sprintf('ERROR_PHP_CONFIG',JTEXT::_('MODULE_NAME'),$SafeItemID, $sourceSite),JTEXT::_('ERROR_PHP_CONFIG_INFO'));
      }
    }

    if ($resp->Item) {
      return $resp->Item;
    }else{
      JError::raiseWarning(102,JTEXT::sprintf('ERROR_API_ITEM',JTEXT::_('MODULE_NAME'),$SafeItemID, $sourceSite),JTEXT::_('ERROR_API_ITEM_INFO'));
      return;
    }
  }
}
