# Auction Affiliate - A Joomla! Module for eBay Auctions

>## _To advertise your eBay auction, direct web traffic to it and to draw affiliation revenue_

> This module displays an eBay auction item in a Joomla! web page in a Joomla module position. To display multiple auction items, simply instantiate multiple modules. The auction item(s) can be your own auction, a friend's or that of someone unknown. Through this module, a visitor can go directly to eBay from your website, bid and complete a successful transaction. You can choose what information about the item you want to display and optionally wether you want to enable eBay affiliation. If you are a registered affiliate, you will receive an affiliation fee in recognition of having facilitated the succcessfull transaction.

## Why is this module useful?

* It offers an easy way of showing your website visitors what you are selling on eBay
* Simple configuration - the mimimum configuration you need to do to set it up in an good position in your template and to enter the eBay Item number in the module configuration. There are lots of other nice goodies to configure in the module too, by the way.
* Manages affiliation tracking of your selected eBay auction.
* Use it to direct web traffic to your eBay auction, which means that more people will bid, which in turn means that you can potentially realize a higher final auction sale value.
* Remember, you can set this affiliation up for anybody's item on eBay, not just your own items.

## What does it do?

It displays the following:

* Auction item image
* Item Description
* Date and Time of auction/sale end
* Remaining time countdown to auction/sale end
* Number of bids so far
* Price and currency
* Location and country of item
* Auction status: Active or Completed

It also manages:

* The retrieval of the auction data
* Tracker affiliation

Supports:

* JSON and XML API encoding
* Uses cURL if PHP's file_get_contents is disabled on your web server (often the case on shared hosting servers)

![This is ow it works](images/moduleinaction_withdetails.png)

# The Care, Feeding and Configuration of your Module

You can only configure one auction item per module. If you want to show 
multiple auction items, then set up multiple modudes instances on your Joomla
website. Since the amount of information you display is so configurable, you
can reduce the module size and display, say, 6 of these modules below each
other in a side column for 6 auction items.

## This is what you can configure

### The frequently-configured bits

* Auction Item Id - a 12-digit number, which you copy and paste from the eBay web page in the Description section where it says: ```"eBay item number: XXXXXXXXXXXX"```, or from the URL
* Comment - you can add an additional comment about the auction item. If left blank, no comment will be displayed.
* eBay API account Id in the form ```XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX```. You get this Id when you register at [http://developer.ebay.com](http://developer.ebay.com). By default, the authors's API Id is set on in the module already to get you started, but strictly speaking, you should replace it with your own API account Id.

### The less frequently-configured bits

* Image width. eBay images are by default 400x300 px in size, which is too large for a typical Joomla module in a margin of a web page. The recommended default is 200 pixels wide.
* Source Site - select the one eBay that this item is hosted on. eBay currenly support 22 such site, so make sure you choose the correct one. 
* Time zone - Auction times are displayed in terms of this time zone. It is useful to use the time zone that the item is located in, but you can alternatively choose the time zone where most of your users will be viewing this item from.

### The occasionally-configured bits

* Display location - displays the town and country where the item is located.
* Display eBay logo - if you are pushed for space, disable this.
* Display image - if you want to save space or only want to display the item's title text, disable this.

### The seldomly-configured bits

* Enable affiliation - if you are a registered affiliate, you can use this item for revenue generation.
* Affiliate Partner - eBay currently works with 8 affiliate partners, including itself. Choose the one that you are registered with. You have enabled affiliation, right?
* Tracking Id - The Id by which you are known to your affiliate partner.

### The mega-geek bits

* Sandbox enable - if you want to end-to-end test entire eBay transaction through this module, this options lets you use the eBay sandbox environment and not incur any transaction fees.
* API Encoding - if you use PHP version >= 5.12, then the API encoding is done with JSON. Older versions of PHP only work with XML.
* Debug - enable this to display the content of the API calls displayed. This setting is not affected by the Global Debug setting, nor does this setting affect the Global one.

## Installation

Steps:

* You have Joomla installed on your webserver, right?
* The module's ZIP package is installed using the standard Joomla Component Installer mechanism.

## Uninstallation

Uninstall the module through the Joomla Extension Manager. You will loose your configuration data, so jot down the details first if they are important to you.

## Support

You can get further support here: [https://github.com/gerritonagoodday/auction-affiliate](https://github.com/gerritonagoodday/auction-affiliate), or contact the author directly at [gerrit@hoekstra.co.uk](# mailto:gerrit@hoekstra.co.uk). Also see [www.hoekstra.co.uk](# www.hoekstra.co.uk) for more details and often and actual eBay item on display.

## Known bugs and Feature Requests

Lodge any issues you find and feature requests here:

[https://github.com/gerritonagoodday/auction-affiliate/issues](https://github.com/gerritonagoodday/auction-affiliate/issues)

## Licensing

This is the brave new world of open source where giants cower in fear of the
little man who releases a nice bit of usefull free software. Well, maybe not
this piece of software, but you get the drift. Anywayz, it's GPL2-licensed.
