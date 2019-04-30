<?php
/*
* @version    $Id: uninstall.mod_auctionaffiliate.php 50 2013-11-17 15:00:32Z gerrit_hoekstra $
* @package    mod_AuctionAffiliate
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

// Will only work in Joomla 1.6 upwards
function mod_uninstall() {
  return JTEXT::_("Uninstallation of the Auction Affiliate module complete.");
}
?>