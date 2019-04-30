<?php
/*
* @version    $Id: default.php 50 2013-11-17 15:00:32Z gerrit_hoekstra $
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

  $display_button   =$params->get("display_button",'1');
  $display_location =$params->get("display_location",'1');
  $display_country  =$params->get("display_country",'0');
  $display_logo     =$params->get("display_logo",'1');
  $display_image    =$params->get("display_image",'1');
  $disable_item     =$params->get("disable_item",'1');
  $comment     = $params->get('comment', '');
  $encoding    = $params->get('encoding', '0');
  $debug       = $params->get('debug', '0');
  //$service_timezone = $params->get('service_timezone', '0');
  $audience_timezone = $params->get('audience_timezone', 'Europe/London');
  $summertimezone_override = $params->get('summertimezone_override');
  $wintertimezone_override = $params->get('wintertimezone_override');
  $date_format = $params->get('date_format','Y-m-d');
  $article_name_height = $params->get('article_name_height',0);

  $link        = $resp->ViewItemURLForNaturalSearch;
  $title       = $resp->Title;
  $location    = $resp->Location;
  $country     = $resp->Country;
  $bidCount    = $resp->BidCount;

  // Get current / sold price
  $price =  ($encoding == 1) ? $resp->ConvertedCurrentPrice : $resp->ConvertedCurrentPrice->Value;

  // Pad trailing zeros in prices
  // $price = "8.5" --> "8.50"
  $price=preg_replace('/\.(\d)\s*$/','.${1}0',$price);
  // $price = "8." --> "8.00"
  $price=preg_replace('/\.\s*$/','.00',$price);
  // $price = "8" -->  "8.00"
  $price=preg_replace('/^(\d*)$/','${1}.00',$price);

  // Get currency
  $currency = $resp->ConvertedCurrentPrice->CurrencyID; // does not work when not using JSON

  if($debug){
    echo "<strong>Price:</strong><br/>";
    echo $price."<br/>";
    echo "<strong>Currency:</strong><br/>";
    echo $currency."<br/>";
    echo "<strong>ListingStatus:</strong><br/>";
    echo $resp->ListingStatus."<br/>";
  }

  // Auction Status: Active, Completed, Ended
  switch($resp->ListingStatus){
    case "Active":
      $listing_status = JTEXT::_('AUCTION_STATUS_ACTIVE');
      break;
    case "Completed":
      $listing_status = "<strong>".JTEXT::_('AUCTION_STATUS_COMPLETED')."</strong>";
      // disable item if complete
      /* TODO
      if($disable_item){
      }
      */
      break;
    case "Ended":
      $listing_status = "<strong>".JTEXT::_('AUCTION_STATUS_ENDED')."</strong>";
      break;
    default:
      // Custom code
      $listing_status = "<strong>".JTEXT::_('AUCTION_STATUS_ENDED')."</strong>";
      break;
  }

  // Auction Type
  $buyitnow_item=0;
  switch($resp->ListingType){
    case "FixedPrice":
    case "FixedPriceItem":
    case "StoresFixedPrice":
      $price_status = JTEXT::_('BUYITNOW_PRICE');
      $buyitnow_item=1;
      break;
    default:
      // "Chinese" is standard eBay auction
      if($resp->ListingStatus=="Active"){
        $price_status = JTEXT::_('CURRENT_PRICE');
      }else{
        // It is now sold
        $price_status = JTEXT::_('FINAL_PRICE');
      }
      break;
  }

  /*
  Not used
  $d1=new JDate($resp->StartTime);
  $start_date = $d1->toFormat('%Y-%m-%d');
  $start_time = $d1->toFormat('%H:%M:%S %Z');
  */

  // Times given by ebay API are in UTC i.e. $resp->EndTime; has Zulu time included
  // DO NOT USE Joomla's JDate class - it is broken!
  $d2=new DateTime($resp->EndTime);
  // Express the date time in terms of audience time zone
  $d2->setTimezone(new DateTimeZone($audience_timezone));

  $end_date = $d2->format($date_format);

  // Assume dayight savings only happen in summer
  if($d2->format('I')==1){
    if($summertimezone_override){
      $end_time = $d2->format('H:i:s ').$summertimezone_override;
    }else{
      $end_time = $d2->format('H:i:s T');
    }
  }else{
    if($wintertimezone_override){
      $end_time = $d2->format('H:i:s ').$wintertimezone_override;
    }else{
      $end_time = $d2->format('H:i:s T');
    }
  }

  // TODO: Add java script to dynamically count down of remaining time values
  // Auction time left in ISO8601 Duration format
  if(floatval(substr(phpversion(),0,3))<5.3){
    // TODO: Better PHP to crack open this crap ISO format string - when DateInterval class can't be used PHP<5.3
    $timeleft = $resp->TimeLeft;
    // Split this: P1DT16H25M43S or PT1H14M17S or PT59M35S
    $a=preg_split("/P|D|T|H|M|S/",$timeleft);
    // Possible array mapping after split, where D=day, HH=hours, MM=minutes, SS=seconds
    // P1DT16H25M43SArray ( [0] => [1] => D [2] =>    [3] => HH [4] => MM [5] => SS [6] => )
    // PT1H14M17SArray (    [0] => [1] =>   [2] => HH [3] => MM [4] => SS [5] =>           )
    // PT59M35SArray (      [0] => [1] =>   [2] => MM [3] => SS [4] =>                     )
    // PT35SArray         ( [0] => [1] =>   [2] => SS [3] =>                               )
    if($a[1]){
      // Days left
      // D H:M:S
      if($a[1]==1){
        // Singlular DAY
        $remaining = sprintf("%d %s %d:%02d:%02d",$a[1],JText::_('DAY'),$a[3],$a[4],$a[5]);
      }else{
        // Plural DAYS
        $remaining = sprintf("%d %s %d:%02d:%02d",$a[1],JText::_('DAYS'),$a[3],$a[4],$a[5]);
      }
    }else{
      // Only hours, minutes and seconds left
      if(count($a)==6){
        // H:M:S
        $remaining = sprintf("%s:%02d:%02d",$a[2],$a[3],$a[4]);
      }else{
        if(count($a)==5){
          // M:S
          $remaining = sprintf("<strong>%dm %02ds</strong>",$a[2],$a[3]);
        }else{
          // S only
          $remaining = sprintf("<strong>%ds</strong>",$a[2]);
        }
      }
    }
  }else{
    // Only works in PHP5.3++. Still no access to protected data members, so use "format" member function
    $di = new DateInterval($resp->TimeLeft);
    if($di->format('%d')>0){
      // Days left
      // D H:M:S
      if($di->format('%d')==1){
        // Singilular DAY
        $remaining = $di->format('%d '.JText::_('DAY').' %h:%I:%S');
      }else{
        // Plural DAYS
        $remaining = $di->format('%d '.JText::_('DAYS').' %h:%I:%S');
      }
    }else{
      // Only hours, minutes and seconds left
      if($di->format('%h')>0){
        // H:M:S
        //$remaining = $di->format('0 '.JText::_('DAYS').' %h:%I:%S');
        $remaining = $di->format('%h:%I:%S');
      }else{
        // Only Minutes & Seconds
        if($di->format('%i')>0){
          // M:S
          $remaining = $di->format('<strong>%im %ss</strong>');
        }else{
          // Seconds only
          $remaining = $di->format('<strong>%ss</strong>');
        }
      }
    }
  }

  // Get image URI
  $image_uri = ($resp->PictureURL[0]) ? $resp->PictureURL[0] : $resp->GalleryURL;

  /*
   * TODO: Need to figure out how best to use Joomla caching api
  if(cant_use_file_get_contents){
    jimport( 'joomla.cache.storage.file' );
    $cache = new JCacheStorageFile(array('defaultgroup'=>'AuctionAffiliate'));
    $data =  $cache.get($resp->ItemID,'AuctionAffiliate');
    if(!data){
      $data = curl_get_contents($image_uri);
      $cache.store($resp->ItemID,'AuctionAffiliate',$data);
    }
    // How do we stream data from a variable into an image?
  }
  */

  if(IsFileGetContentsOK()){
    $s = getimagesize($image_uri);
  }else{
     if(IscURLInstalled()){
        // Make up our own file caching process.
        // User is required to keep cache directory clean
        $image_fn=sprintf("cache/%s.%s", $resp->ItemID,"jpg");
        if(!file_exists($image_fn) || time()-filectime($image_fn)>86400){
          $content= curl_get_contents($image_uri);
          $h=fopen($image_fn,"a+w");
          fwrite($h,$content);
          fclose($h);
        }
        $s = getimagesize($image_fn);
        if(!$s[0]){
          // Use default eBay image form factor:
          $s[0]=400;
          $s[1]=300;
        }
     }else{
       JError::raiseWarning(103,JTEXT::sprintf('ERROR_PHP_CONFIG',JTEXT::_('MODULE'),$SafeItemID, $sourceSite),JTEXT::_('ERROR_PHP_CONFIG_INFO'));
     }
  }
  $aspect = $s[1] / $s[0];
  $scaled_width = $params->get('image_width','200');
  $scaled_height = $scaled_width * $aspect;

  // TODO: Move this into the style.css file:
  // Button CSS Generated by www.cssbuttongenerator.com - Absolutely brilliant!
?>
<style type="text/css">
.ebaybutton {
  background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #7db9fa), color-stop(1, #0a46a8) );
  background:-moz-linear-gradient( center top, #7db9fa 5%, #0a46a8 100% );
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#7db9fa', endColorstr='#0a46a8');
  background-color:#7db9fa;
  -moz-border-radius:6px;
  -webkit-border-radius:6px;
  border-radius:6px;
  display:inline-block;
  color:#ffffff;
  font-family:Arial;
  font-size:14px;
  font-weight:bold;
  padding:6px 12px;
  text-decoration:none;
} .ebaybutton:hover {
  background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #0a46a8), color-stop(1, #7db9fa) );
  background:-moz-linear-gradient( center top, #0a46a8 5%, #7db9fa 100% );
  filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0a46a8', endColorstr='#7db9fa');
  background-color:#0a46a8;
} .ebaybutton:active {
  position:relative;
  top:2px;
}
</style>
<?php

  // TODO: Use JGrid instead
  // Module Content Display
  if($comment){
    echo "<table class=\"af\" width=\"".$scaled_width."px\" frame=\"void\" rules=\"none\"><tr><td>$comment</td></tr></table>";
  }

  // Display item out of table
  // TODO: Use <td span="2">
  if($display_image){
    if(!IsFileGetContentsOK()){
      // Use cached image
      echo "<div><a href=\"$link\" target=\"_blank\"><img class=\"af\" src=\"$image_fn\" alt=\"".JText::_('VIEW_DETAILS')."\" title=\"$title\" width=\"".$scaled_width."\" height=\"".$scaled_height."\" ></a></div>";
    }else{
      // Get image directly from eBay
      echo "<div><a href=\"$link\" target=\"_blank\"><img class=\"af\" src=\"$image_uri\" alt=\"".JText::_('VIEW_DETAILS')."\" title=\"$title\" width=\"".$scaled_width."\" height=\"".$scaled_height."\" ></a></div>";
    }
  }

  // TODO: Use JGrid
  echo "<table class=\"af\" width=\"".$scaled_width."px\" frame=\"void\" rules=\"none\"><tr>";
  if($display_logo){
    $logo_path = 'modules/'.$module->module."/images/ebay_logo_small.png";
    if($article_name_height>0){
    echo "<td><div style=\"text-align: left; height: ".$article_name_height."px;\"><a href=\"$link\" target=\"_blank\"><img src=\"$logo_path\" alt=\"".JText::_('VIEW_DETAILS')."\" title=\"$title\"></a></td><td><span class=\"af_title\"><a href=\"$link\" target=\"_blank\">$title</a><br/></span></div></td>";
    }else{
    echo "<td><div style=\"text-align: left;\"><a href=\"$link\" target=\"_blank\"><img src=\"$logo_path\" alt=\"".JText::_('VIEW_DETAILS')."\" title=\"$title\"></a></td><td><span class=\"af_title\"><a href=\"$link\" target=\"_blank\">$title</a><br/></span></div></td>";
    }
  }else{
    if($article_name_height>0){
    echo "<td><div style=\"text-align: center; height: ".$article_name_height."px;\"><span class=\"af_title\"><a href=\"$link\" target=\"_blank\">$title</a><br/></span></div></td>";
    }else{
    echo "<td><div style=\"text-align: center;\"><span class=\"af_title\"><a href=\"$link\" target=\"_blank\">$title</a><br/></span></div></td>";
    }
  }
  echo "</tr></table>";

  echo "<table class=\"af\" width=\"".$scaled_width."px\" frame=\"void\" rules=\"none\">";
  if($display_location){
        echo           "<tr><td class=\"af_label\">".JText::_('LOCATION').":</span></td><td>$location";
        echo "</td></tr>";
   }
  if($display_country){
        echo           "<tr><td class=\"af_label\">".JText::_('COUNTRY').":</td><td>$country";
        echo "</td></tr>";
  }
        echo           "<tr><td class=\"af_label\">".$price_status.":</td><td>$price $currency";
        echo "</td></tr><tr><td class=\"af_label\">".JText::_('BIDS').":</td><td>$bidCount";

  if($resp->ListingStatus=="Active"){
        echo "</td></tr><tr><td class=\"af_label\">".JText::_('END_DATE').":</td><td>$end_date";
        echo "</td></tr><tr><td class=\"af_label\">".JText::_('END_TIME').":</td><td>$end_time";
    if($remaining){
        echo "</td></tr><tr><td class=\"af_label\">".JText::_('REMAINING').":</td><td>$remaining";
    }
  }
        echo "</td></tr><tr><td class=\"af_label\">".JText::_('AUCTION_STATUS').":</td><td>$listing_status";
  if($resp->ListingStatus=="Active"){
    if($buyitnow_item){
      if($display_button){
        echo "</td></tr><tr><td align=\"center\" colspan=\"2\"><a href=\"$link\" class=\"ebaybutton\" target=\"_blank\">".JText::_('BUYITNOW')."</a>";
      }else{
        echo "</td></tr><tr><td class=\"af_label\" span=\"2\">".JText::_('BUYITNOW');
      }
    }else{
      if($display_button){
        echo "</td></tr><tr><td align=\"center\" colspan=\"2\"><a href=\"$link\" class=\"ebaybutton\" target=\"_blank\">".JText::_('PLACE_BID')."</a>";
      }
    }
  }
      echo "</td></tr>";
  echo "</table>";
?>
