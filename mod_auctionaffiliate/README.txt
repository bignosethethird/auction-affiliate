AuctionAffiliate Module Version 3.2.0.1 README
----------------------------------------------
$Id: README.txt 50 2013-11-17 15:00:32Z gerrit_hoekstra $

Author: Gerrit Hoekstra <gerrit@hoekstra.co.uk>
        www.hoekstra.co.uk

Introduction
------------
Advertise and direct web traffic to your eBay auction, or draw affiliation
revenue from other eBay auctions!
  
This module displays an eBay auction item on a Joomla CMS web page. The
auction item can be your own auction, a friend's or that of someone unknown.
Through this module, a visitor can go directly to eBay from your website, bid
and complete a successful transaction. If you are a registered affiliate, you
can make a little money too. You can choose what information you want to 
display and optionally whether you want to enable eBay affiliation.

Why is this module useful?
--------------------------
    * Easy way of showing your website visitors what you are selling on eBay
    * Simple configuration - the mimimum is to simply enter the eBay Item 
      number in the module configuration. There are lots of other nice goodies
      to configure, by the way
    * Affiliate portal to eBay
    * Direct web traffic to your eBay auction, which means that more people
      will bid, which in turn means that you can potentially realize a higher
      final sale value. If you joined an affiliate scheme and have set this up
      here and this is someone else's auction, then your affiliate proceeds
      will of course higher as a result.

What does it do?
----------------
It displays the following:
    * Auction item image
    * Description heading text
    * Date and Time the auction ends
    * Remaining time before auction ends
    * Number of bids so far
    * Price and currency
    * Location and country of item
    * Auction status: Active or Completed
It also manages:
    * the retrieval of the auction data
    * affiliation 

Care, Feeding and Configuration of your module
----------------------------------------------
You can only configure one auction item per module. If you want to show 
multiple auction items, then set up multiple modudes instances on your Joomla
website. Since the amount of information you display is so configurable, you
can reduce the module size and display, say, 6 of these modules below each
other for 6 auction items.

This is what you can configure:

The frequently-configured bits:
    * Auction Item Id - a 12-digit number, which you simple copy and paste
      from the eBay web page.
    * Comment - you can add an additional comment about the auction item. If
      left blank, no comment will be displayed.
    * eBay API account Id in the form XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX.
      You get this Id when you register at http;//developer.ebay.com. By
      default, the authors's API Id is there to get you started.
The less frequently-configured bits:
    * Image width. eBay images are by default 400x300 px in size, which is too
      large for a typical Joomla module in a margin of a web page. The 
      recommended default is 200 pixels wide.
    * Source Site - select the one eBay that this item is hosted on. eBay
      currenly support 22 such site, so make sure you choose the correct one. 
    * Time zone - Auction times are displayed in terms of this time zone. It
      is useful to use the time zone that the item is located in, but you can
      alternatively choose the time zone where most of your users will be
      viewing this item from.
The occasionally-configured bits:
    * Display location - displays the town and country where the item is 
      located.
    * Display eBay logo - if you are pushed for space, disable this.
    * Display image - if you want to save space or only want to display the
      item's title text, disable this.
The seldomly-configured bits:
    * Enable affiliation - if you are a registered affiliate, you can use this
      item for revenue generation.
    * Affiliate Partner - eBay currently works with 8 affiliate partners, 
      including itself. Choose the one that you are registered with. You have
      enabled affiliation, right?
    * Tracking Id - The Id by which you are know to your affiliate partner.
The mega-geek bits:
    * Sandbox enable - if you want to end-to-end test entire eBay transaction
      through this module, this options lets you use the eBay sandbox 
      environment and not incur any transaction fees.
    * API Encoding - if you use PHP version >= 5.12, then the API encoding is
      done with JSON. Older versions of PHP only work with XML.
    * Debug - enable this to display the content of the API calls displayed.
      This setting is not affected by the Global Debug setting, nor does this
      setting affect the Global one.

Installation
------------
Steps:
    * You have Joomla installed on your webserver, right?
    * If this module is already installed, uninstall it first. 
    * The module's ZIP package is installed using the standard Joomla 
      Component Installer mechanism.

Uninstallation
--------------
Uninstall the module through the Joomla Extension Manager. You will loose your
configuration data, so jot down the details first if it is important to you.

Support
-------
www.hoekstra.co.uk
gerrit@hoekstra.co.uk

Changelog
---------
Now lives happily inside the Joomla 3.x framework.

Known bugs
----------
Surely not! OK then, probably many.
Lodge any issues you find here:
http://forge.joomla.org/gf/project/auction_afflte/tracker

Feature Requests
----------------
    * Live count-down during the last hour, just like on the eBay website.
    * Live updates of bids and price. Currently, a screen refresh is required.
    * Error handling when the eBay website is slow / down for whatever reason.
  From Stefan Halbscheffel, who suggested these:
    * A link to the autioneer's other items on sale. This may have some 
      implication on affiliation issues.
    * Make the auction disappear, i.e. become unpublished, when the auction
      has ended. 
    * Configuration parameter to set the font size of the item name

Licensing
---------
This is the brave new world of open source where giants cower in fear of the
little man who releases a nice bit of usefull free software. Well, maybe not
this piece of software, but you get the drift. Anywayz, it's GPL2, m'kay?
