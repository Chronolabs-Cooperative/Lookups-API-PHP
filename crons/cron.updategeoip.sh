## BASH !! SHELL BEACH !!
##
## crontab script suggestion
##
## * * */1 * * sh /path/to/lookupsapi/cron/cron.updategeoip.php
##
##
mkdir /usr/share/GeoIP

# geoip-update.sh -- update geoip lite database(s).
# author: massimo.scamarcia@gmail.com

#prg="wget --quiet"
prg="wget"
download_path="/usr/share/GeoIP/download"
geolite_path="/usr/share/GeoIP"

#set -e
[ -d $download_path ] || mkdir $download_path
if [ ! -e $geolite_path ]; then
        echo "Unable to find GeoIP directory: $geolite_path"
        exit 1
fi

cd $download_path

$prg http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
if [ ! -e $download_path/GeoIP.dat.gz ]; then
        echo "Unable to find GeoIP.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIP.dat.gz > $geolite_path/GeoIP.dat
rm -f $download_path/GeoIP.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPASNum.dat.gz
if [ ! -e $download_path/GeoIPASNum.dat.gz ]; then
        echo "Unable to find GeoIPASNum.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPASNum.dat.gz > $geolite_path/GeoIPASNum.dat
rm -f $download_path/GeoIPASNum.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPCity.dat.gz
if [ ! -e $download_path/GeoIPCity.dat.gz ]; then
        echo "Unable to find GeoIPCity.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPCity.dat.gz > $geolite_path/GeoIPCity.dat
rm -f $download_path/GeoIPCity.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPDomain.dat.gz
if [ ! -e $download_path/GeoIPDomain.dat.gz ]; then
        echo "Unable to find GeoIPCity.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPDomain.dat.gz > $geolite_path/GeoIPDomain.dat
rm -f $download_path/GeoIPDomain.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPISP.dat.gz
if [ ! -e $download_path/GeoIPISP.dat.gz ]; then
        echo "Unable to find GeoIPISP.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPISP.dat.gz > $geolite_path/GeoIPISP.dat
rm -f $download_path/GeoIPISP.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPNetSpeedCell.dat.gz
if [ ! -e $download_path/GeoIPNetSpeedCell.dat.gz ]; then
        echo "Unable to find GeoIPNetSpeedCell.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPNetSpeedCell.dat.gz > $geolite_path/GeoIPNetSpeedCell.dat
rm -f $download_path/GeoIPNetSpeedCell.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPOrg.dat.gz
if [ ! -e $download_path/GeoIPOrg.dat.gz ]; then
        echo "Unable to find GeoIPOrg.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPOrg.dat.gz > $geolite_path/GeoIPOrg.dat
rm -f $download_path/GeoIPOrg.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPRegion.dat.gz
if [ ! -e $download_path/GeoIPRegion.dat.gz ]; then
        echo "Unable to find GeoIPRegion.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPRegion.dat.gz > $geolite_path/GeoIPRegion.dat
rm -f $download_path/GeoIPRegion.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz
if [ ! -e $download_path/GeoLiteCity.dat.gz ]; then
        echo "Unable to find GeoLiteCity.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoLiteCity.dat.gz > $geolite_path/GeoLiteCity.dat
rm -f $download_path/GeoLiteCity.dat.gz

$prg http://geolite.maxmind.com/download/geoip/database/GeoIPv6.dat.gz
if [ ! -e $download_path/GeoIPv6.dat.gz ]; then
        echo "Unable to find GeoIPv6.dat.gz!"
        exit 1
fi
gunzip -c $download_path/GeoIPv6.dat.gz > $geolite_path/GeoIPv6.dat
rm -f $download_path/GeoIPv6.dat.gz
cd /usr/share/GeoIP
chmod -fv 0777 .
rm *
echo "Downloading GEOIP Database Resources!\n"
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIP.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoIPv6.dat
wget https://github.com/maxmind/geoip-api-php/raw/master/tests/data/GeoLiteCityIPv6.dat
chown -fv www-data:root .
chmod -fv 0644 .
