# Wellness Annuary "Bien-Être"
Project for the course "Dynamic website project".

## Installation
### 1. Clone the repository
  `git clone https://github.com/ngihoul/wellnessAnnuary.git`
### 2. Up containers
  Run docker-compose.yml file with command : `sudo docker-compose up --build [-d] `
### 3. Setup config
#### 3.1 Access _www_annuaire_ container
  `docker exec -ti www_annuaire bash`
#### 3.2 Install dependencies
  `composer install`
#### 3.3 Create database
  `php bin/console doctrine:database:create`

## Tools & technologies
* Docker & Docker-compose
* Symfony 6.0.0
* PHP 8.0.12
* PHPMyAdmin 5.1.1
* MySQL 8.0.27
* SaSS