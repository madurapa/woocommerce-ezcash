== Dialog eZ Cash payment gateway for Woocommerce ==

Contributors: maduka
Tags: ezcash, woocommerce, dialog, etisalat, hutch, payment, payment gateway, ipg, mobile payment, emoney, ewallet, lkr, rs, rupees, srilanka
Requires at least: 4.4
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 1.0.6
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Dialog eZ Cash WooCommerce Payment Gateway allows you to accept payments via Dialog, Etisalat, and Hutch mobile phones.

== Description ==

This is an eZ Cash payment gateway for WooCommerce.  
eZ Cash is a payment gateway that allows you to accept payments online via Dialog, Etisalat, and Hutch mobile phones.  
To get an eZ Cash merchant account visit their website by clicking [here](http://www.ezcash.lk)  
Dialog eZ Cash WooCommerce Payment Gateway allows you to accept payments via Dialog, Etisalat, and Hutch mobile phones.  
With this eZ Cash WooCommerce Payment Gateway plugin, you will be able to accept the following payment methods in your shop:

* __Dialog Mobile Phone__
* __Etisalat Mobile Phone__
* __Hutch Mobile Phone__

= Note =

This plugin meant to be used by merchants in Sri Lanka.  
I have always loved using open source, and I believe in giving back to open source as much as I benefit from it.  
The intention to make this plugin is to help those who are willing to start an online business with a low budget, so I'm expecting nothing but any feedbacks, suggestions, feature requests, reporting bugs/issues, and
rate this plugin.

= Plugin Features =

* __Accept payment__ Dialog, Etisalat, and Hutch mobile phones.
* __Seamless integration__ into the WooCommerce checkout page.

= Issues, Suggestions, Feature Request =

If you have suggestions, features request, report bugs/issues, feel free to use one of these [GitHub Issues](https://github.com/madurapa/woocommerce-ezcash/issues) or [WordPress Support](https://wordpress.org/support/plugin/dialog-ez-cash-payment-gateway-for-woocommerce/).

= Contribute =

To contribute to this plugin feel free to fork on [GitHub](https://github.com/madurapa/woocommerce-ezcash).

== Installation ==

= Automatic Installation =

* Login to your WordPress Admin area
* Go to "Plugins > Add New" from the left menu
* In the search box type "Dialog eZ Cash Payment Gateway for WooCommerce"
* From the search result you will see "Dialog eZ Cash Payment Gateway for WooCommerce" click on "Install Now" to install the plugin
* A popup window will ask you to confirm your wish to install the Plugin.

= Note: =

If this is the first time you've installed a WordPress Plugin, you may need to enter the FTP login credential information. If you've installed a Plugin before, it will still have the login information. This information is available through your web server host.

* Click "Proceed" to continue the installation. The resulting installation screen will list the installation as successful or note any problems during the install.
* If successful, click "Activate Plugin" to activate it.
* Open the settings page for WooCommerce and click the "Payment Gateways," tab.
* Click on the sub-tab for "eZ Cash".
* Configure your "eZ Cash" settings. See below for details.

= Manual Installation =

1. Download the plugin zip file
2. log in to your WordPress Admin. Click on "Plugins > Add New" from the left menu.
3. Click on the "Upload" option, then click "Choose File" to select the zip file from your computer. Once selected, press "OK" and press the "Install Now" button.
4. Activate the plugin.
5. Open the settings page for WooCommerce and click the "Payment Gateways," tab.
6. Click on the sub-tab for "eZ Cash".
7. Configure your "eZ Cash" settings. See below for details.

= Configure the plugin =

To configure the plugin, go to __WooCommerce > Settings__ from the left menu, then click "Payment Gateways" from the top tab. You should see __"eZ Cash"__ as an option at the top of the screen. Click on it to configure the payment gateway.

_You can select the radio button next to eZ Cash from the list of payment gateways available to make it the default gateway._

* __Enable/Disable__ - check the box to enable eZ Cash Payment Gateway.
* __Title__ - allows you to determine what your customers will see this payment option as on the checkout page.
* __Description__ - controls the message that appears under the payment fields on the checkout page.
* __Merchant Code__  - Enter your Merchant Alias here. You will get this in your eZ Cash merchant account [eZ Cash](http://www.ezcash.lk).
* __Request URL__  - enter the eZ Cash server request URL here. You will get this in your eZ Cash merchant account [eZ Cash](http://www.ezcash.lk).
* __Public Test Key__  - enter your Public Test Key here. You will get this in your eZ Cash merchant account [eZ Cash](http://www.ezcash.lk).
* __Private Test Key__  - enter your Private Test Key here. You will get this in your eZ Cash merchant account [eZ Cash](http://www.ezcash.lk).
* __Public Live Key__  - enter your Public Live Key here. You will get this in your eZ Cash merchant account [eZ Cash](http://www.ezcash.lk).
* __Private Live Key__  - enter your Private Live Key here. You will get this in your eZ Cash merchant account [eZ Cash](http://www.ezcash.lk).
* __Test Mode__  - tick to enable test mode.
* Click on __Save Changes__ for the changes you made to be affected.

== Frequently Asked Questions ==

 What Do I Need To Use The Plugin 

1. You need to have the WooCommerce plugin installed and activated on your WordPress site.
2. You need to open a merchant account on [eZ Cash](http://www.ezcash.lk)

== Screenshots ==

1. eZ Cash WooCommerce Payment Gateway setting page



2. Test Mode notification will always be displayed in the admin backend until when test mode disabled



3. eZ Cash WooCommerce Payment method on the checkout page



4. Successful Transaction page



== Changelog ==

__1.0.6__  
Release Date: June 15, 2021

* Fixed PUBLIC and PRIVATE keys malformed issue.
* Fixed not showing notice message when the payment has been failed.
* Fixed get total order amount method.

__1.0.5__  
Release Date: September 15, 2020

* Fixed a minor bug
* Tested with the latest WordPress and WooCommerce

__1.0.4__  
Release Date: June 21, 2020

* Fixed missing PUBLIC and PRIVATE key templates
* Tested with the latest WordPress and WooCommerce

__1.0.3__  
Release Date: June 01, 2017

* Added more Sri Lankan rupee codes besides the standard ISO code as some plugins required

__1.0.2__  
Release Date: February 26, 2017

* First release

== Upgrade Notice ==

No updates with this release
