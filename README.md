<<<<<<< HEAD
## Chronolabs Cooperative presents

# IPv4, IPv6 Locational Lookups REST API v2.3.10

## Geolocational information for IP Addresses + NetBIOS referee's - http://lookups.snails.email

### Author: Simon Antony Roberts <simon@snails.email>

The following API allows for the searching for geolocation that longitude+latitude locations for IPv4, IPv6 and netbios routes, it uses two seperate sources for the information and you will find the installation easy to operate and do.

# Setting Up the environment in Ubuntu/Debian

There is a couple of extensions you will require for this API to run you need to execute the following at your terminal bash shell to have the modules installed before installation.

    $ sudo apt-get install php-geoip geoip-database php-curl php-mbstring php-exif php-xml -y
    
This doesn't install the complete maxmind geoip database only the basic one you will have to dig around on https://github.com/maxmind or one of Maxmind resources to find all the *.dat files for the full implementation of this api.

You will also require an ipinfodb.com API Key for the secondary search functions this uses two polls of information for a resource you will be ask about the *.dat locations as well as the IPInfoDB.com API Key during the installation.

You will also need to run the following shell script before installation script is executed from the server or development environment shell bash

    $ sh /path/to/lookupsapi/crons/cron.updategeoip.php

# Apache Module - URL Rewrite

The following script goes in your API_ROOT_PATH/.htaccess file

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    RewriteRule ^v([0-9]{1,2})/(country|city|geoip|geocity|geonetspeed|geonetspeedcell|geoorg|geoisp|georegion)/(.*?)/(raw|html|serial|json|xml).api ./index.php?version=$1&mode=$2&ip=$3&output=$4 [L,NC,QSA]
    RewriteRule ^v([0-9]{1,2})/(html|post)/(.*?)/(form).api ./form.php?version=$1&mode=$2&session=$3&output=$4 [L,NC,QSA]

To Turn on the module rewrite with apache run the following:

    $ sudo a2enmod rewrite
    $ sudo service apache2 restart

# Cron Jobs - Scheduled Tasks

There is a couple of cron jobs that need to run on the system in order for the system to run completely within versioning specifications to get to the cron scheduler in ubuntu/debian run the following

    $ sudo crontab -e
    
once in the cron scheduler put these lines in making sure the paths resolution is correct as well as any load balancing you have to do

    */1 * * * * /usr/bin/php -q /var/www/lookups.snails.email/crons/cron.queries.php
    */1 * * * * /usr/bin/php -q /var/www/lookups.snails.email/crons/cron.queries.php
    */1 * * * * /usr/bin/php -q /var/www/lookups.snails.email/crons/cron.queries.php
    */3 * * * * /usr/bin/php -q /var/www/lookups.snails.email/crons/cron.callback.php
    */3 * * * * /usr/bin/php -q /var/www/lookups.snails.email/crons/cron.callback.php
    */3 * * * * /usr/bin/php -q /var/www/lookups.snails.email/crons/cron.callback.php
    * * */1 * * sh /var/www/lookups.snails.email/crons/cron.updategeoip.php
=======
# Chronolabs Cooperative ~ Ipv4, TLD Geospatial Lookups API ~ http://lookups.labs.coop
>>>>>>> parent of 71fffd0... Version Update ~ IPv4, IPv6 Locational Lookups REST API v2.3.9
