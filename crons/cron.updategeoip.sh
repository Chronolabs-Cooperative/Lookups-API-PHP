## BASH !! SHELL BEACH !!
##
## crontab script suggestion
##
## * * */1 * * sh /path/to/lookupsapi/cron/cron.updategeoip.php
##
##
mkdir /usr/share/GeoIP
cd /usr/share/GeoIP
rm *
echo "Downloading GEOIP Database Resources!\n"
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIP.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPASNum.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPCity.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPDomain.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPISP.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPNetSpeedCell.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPOrg.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPRegion.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPv6.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoLiteCityIPv6.dat
