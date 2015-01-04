#!/usr/bin/env bash

export DEBIAN_FRONTEND=noninteractive
adduser www-data vagrant
adduser vagrant www-data
cd /vagrant

apt-get update
apt-get install -y snmp
apt-get install -y acl
apt-get install -y mysql-server
apt-get install -y mysql-client
apt-get install -y apache2
apt-get install -y php5
apt-get install -y php-pear
apt-get install -y php5-mysql
apt-get install -y php5-curl
apt-get install -y php5-gd
apt-get install -y php5-intl
apt-get install -y php5-imagick
apt-get install -y php5-imap
apt-get install -y php5-mcrypt
apt-get install -y php5-memcache
apt-get install -y php5-recode
apt-get install -y php5-snmp
apt-get install -y php5-tidy
apt-get install -y php5-xmlrpc
apt-get install -y php5-xsl
apt-get install -y phpmyadmin
apt-get install -y php5-curl
apt-get install -y php5-json
apt-get install -y php5-readline
apt-get install -y php5-xdebug
apt-get install -y git

if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant /var/www
fi

a2dissite 000-default.conf
cp vagrant.conf/virtualhost.conf /etc/apache2/sites-available/
a2enmod rewrite
a2ensite virtualhost.conf
rm -f /etc/php5/apache2/php.ini
cp vagrant.conf/php.ini /etc/php5/apache2/
service apache2 reload

curl -s http://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer install

php app/console doctrine:database:create
php app/console doctrine:schema:create

echo "cd /vagrant/" >> /home/vagrant/.bashrc