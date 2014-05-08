Loading Deck Billing
=======

This is the Loading Deck billing application that was until early May 2014, offered as SaaS. We have since decided to refocus our company on consultancy and offer this as a free product under the Apache licence. Please familiarise yourself with the Apache licence before downloading this software.

This software is a large project and has involved a number of developers to date. Most notably, it was managed by [James Hadley](http://www.sysadmin.co.uk/) under [Loading Deck Limited](http://www.loadingdeck.com). If you like the software, please consider hiring Loading Deck as your web consultants.

Key features of this software include module support (but unfortunately no modules) for web hosting billing, invoicing, estimates, project management, inventory management, bulk data import/export, recurring billing, integration with Paypal, Stripe and GoCardless, and great aesthetics.

### Installation
1. Get a (virtual) server. This app is quite demanding and doesn't work well on shared hosting. 1GB RAM is probably enough.
2. Install a web server and PHP 5.4. We recommend Apache because it's easy to use and doesn't involve writing lots of additional rewrite rules.
3. Install Wkhtmltopdf. We found that version 0.9.6 works best.
4. Clone the repository using Git
5. Point your web server to the "web" directory
6. Change the parameters commented in app/config/parameters.yml and app/config/config.yml
7. Run `php composer.phar update` to get the vendor packages
8. Import the SQL file
9. Run `php app/console assets:install` to generate the static assets
10. Add `php app/console app_admin:generate_recurring` to your crontab to run daily
11. Log in with username "admin" and password "123456" at URL "/login"

If you need additional help, please consider our [forum](http://community.loadingdeck.com) that you can use to discuss the software with us. Paid support and development are also available by email and telephone (see above).

### Bugs and Contributions
Both issues and PR are welcome via the Github repository. If you know Symfony then we'd appreciate you taking the time to send us a PR. Issues and PRs will be reviewed within our spare time. We are aware of a number of bugs already, from both before and during the transition away from SaaS. Use this software at your own risk!

### Third Party Credits
 * [KendoUI](http://www.kendoui.com) 
 * [Highcharts](http://www.highcharts.com) 
 * [Bootstrap](http://www.getbootstrap.com) 
 * [JQuery](http://www.jquery.com) 

Please let us know if we've forgotten anyone.
