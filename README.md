# Start Development STEP
## 1. Initial Database
### If you're use Docker skip this. 
execute init.sql file with DBMS or in _.docker_ [here](https://github.com/fxurteenz/Trash-Bank/blob/main/.docker/init.sql).
## 2. create .env
```.env
DB_HOST= # localhost and etc, Docker default is database
DB_PORT=3306
DB_DATABASE= # your database name, Docker default is waste_bank
DB_USERNAME= # your db user, Docker default is MYSQL_USER
DB_PASSWORD= # your db pass, Docker default is MYSQL_PASSWORD
JWT_SECRET = # create your own
SALT_ROUND = 12 #recommend
```
## 3. install composer global 
following [Composer official](https://getcomposer.org/download/) then in **_/App_** run this
```bash
composer install
```
## 4. Run 
you have 3 ways
1. _PHP Cli_
in **_/App/public_** run this
```bash
PHP -S locahost:8000
```
2. _Docker_
in _Root_ of project run this
```bash
docker-compose up 
#or
docker compose up
```
3. _XAMPP Apache MAMP httpd_
- move _/App_ to _htdocs_
- set virtual server *DOCUMENT_ROOT* to _/App/public_
