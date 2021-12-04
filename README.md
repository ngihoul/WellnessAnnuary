# Wellness Annuary "Bien-Être"
Project for the course "Dynamic website project".


## Installation
### 1. Up containers
Run docker-compose.yml file with command : `sudo docker-compose up --build [-d] `
### 2. Define database
Modify DATABASE_URL in [.env](annuaire/.env) file.  
For example : `DATABASE_URL=mysql://username:password@127.0.0.1:3306/wellnessAnnuary`

## Tools & technologies
* Docker & Docker-compose
* Symfony 6.0.0
* PHP 8.0.12
* PHPMyAdmin 5.1.1
* MySQL 8.0.27