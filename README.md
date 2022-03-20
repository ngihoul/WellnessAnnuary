# Wellness Annuary "Bien-Être"
Project for the course "Dynamic website project".

## Installation
### 1. Clone the repository
`git clone https://github.com/ngihoul/wellnessAnnuary.git`
### 2. Up containers
Access to the file directory `cd <directory>`  
Run docker-compose.yml file with command : `sudo docker-compose up --build [-d] `
### 3. Prepare MySQL container
#### 3.1 Copy SQl file to MySQL container
`docker cp ./dump.sql db_annuaire:/home`
#### 3.2 Run MySQL container
`docker exec -it -u root db_annuaire bash`
#### 3.3 Import SQL file to MySQL Database
`mysql wellnessAnnuary < /home/dump.sql`
#### 3.3 Exit MySQL container
`exit`
### 4. Setup config
#### 4.1 Access _www_annuaire_ container
`sudo docker exec -ti www_annuaire bash`
#### 4.2 Install dependencies
`composer install`
#### 4.3 Create database
`php bin/console doctrine:database:create`
#### 4.4 Doctrine migration
`php bin/console doctrine:migrations:migrate`
#### 4.5 NPM install & build
`npm install`
`npm run build`

### 4. Voilà
Navigate to http://localhost:8000/

## Tools & technologies
* Docker & Docker-compose
* Symfony 6.0.2
* PHP 8.0.12
* PHPMyAdmin 5.1.1
* MySQL 8.0.27
* SaSS