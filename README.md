# Wellness Annuary "Bien-Être"
Project for the course "Dynamic website project".

## Getting started
### 1. Clone the repository
`git clone https://github.com/ngihoul/wellnessAnnuary.git`
### 2. Up containers
Access to the file directory : `cd wellnessAnnuary`  
Run docker-compose.yml file with command : `sudo docker-compose up --build [-d]`
### 3. Copy SQL file to MySQL container
`sudo docker cp ./dump.sql db_annuaire:/home`
### 4. Setup www container & database
#### 4.1 Access _www_annuaire_ container
`sudo docker exec -ti www_annuaire bash`
#### 4.2 Install dependencies
`composer install`
#### 4.3 Create database
`php bin/console doctrine:database:create`
#### 4.4 NPM install & build
`npm install`
`npm run build`
#### 4.5 Give access to _public/_ directory
`chmod -R 777 public/`
#### 4.6 Exit www container
`exit`
### 5. Import SQL data into database
#### 5.1 Run MySQL container
`sudo docker exec -it -u root db_annuaire bash`
#### 5.2 Import SQL file to MySQL Database
`mysql wellnessAnnuary < /home/dump.sql`
#### 5.3 Exit MySQL container
`exit`
### 4. Voilà
Navigate to http://localhost:8000/

## Tools & technologies
* Docker & Docker-compose
* Symfony 6.0.2
* PHP 8.0.12
* PHPMyAdmin 5.1.1
* MySQL 8.0.27
* SaSS
* Maildev
* Cron jobs