#!/bin/bash
sudo apt-get update && sudo apt-get upgrade
############################################################
#install python 
############################################################

sudo add-apt-repository ppa:fkrull/deadsnakes -y 
sudo apt-get update
sudo apt-get install python3.5

############################################################
#install LAMP
############################################################
#Install Apache
sudo apt-get install apache2 -y 

# Install MySQL
sudo apt-get install mysql-server -y 
sudo apt-get install phpmyadmin -y 

#Install PHP
sudo apt-get install php7.0 php-pear libapache2-mod-php7.0 php7.0-mysql -y

#restart server 
sudo /etc/init.d/apache2 restart

############################################################
#DEPLOYMENT API
############################################################
cd Desktop/
#git clone server
git clone https://github.com/softwarejimenez/API_puja_por_tu_resi.git
cd API_puja_por_tu_resi/

#install composer 
sudo apt install composer -y
composer install

#create database API_puja_por_tu_resi
composer install
php bin/console doctrine:database:create
mysql -u root -p API_puja_por_tu_resi < backup_DB_API_puja_por_tu_resi.sql

#run the Server
php bin/console server:run

############################################################
#CRONTAB automatically job
############################################################
crontab -e
job: 0 23 * * *  python3.5 /home/jimenez/Desktop/API_puja_por_tu_resi/periodical_request/assigne_room.py >> /home/jimenez/Desktop/API_puja_por_tu_resi/periodical_request/output_log.txt

############################################################
#DEPLOYMENT CLIENT
############################################################
cd /var/www/html/
sudo git clone https://github.com/softwarejimenez/web_puja_por_tu_resi.git

#now you can open the web :http://127.0.0.1/web_puja_por_tu_resi/#!/welcome 


