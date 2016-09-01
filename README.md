# README #

#Install Method

## Source install

1. git clone https://github.com/topflyingdragon/laravel_sample

2. composer install

## Server install

### on local server

1. install mysql

2. create empty db.

3. change dbname, db username, password in '<project_root_folder>/htdocs/config/database.php' line 7~12

4. run following script on command prompt for create tables.

5. php htdocs/artisan migrate --force

6. change document root to '<project_root_folder>/htdocs/public'

### on Bluemix Server

1. login blumix server -> dashboard -> create new app

2. add following services

   Concept Insights, Personality Insights, ClearDB MySQL

3. open command prompt and run following command.

   cf push <app name>

run and play

Good luck.