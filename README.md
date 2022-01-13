# catalyst-coding-challenge

Catalyst Coding Project

### Note

I will be using docker for this coding project, but the script can run without docker please look at the assumptions file.

## Setup Instructions

1. To create the containers in the terminal navigate to the location of the docker-compose.yml file:

```
docker-compose up --build
```

2. To connect to the php container

```
docker exec -it php-app bash
```

3. T connect to the mysql container ('password')

```
docker exec -it php_db mysql -u root -p
```

## Script Command Line Directives (Options)

-   --file=[csv file name] – this is the name of the CSV to be parsed
-   --create_table – this will cause the MySQL users table to be built (and no further action will be taken)
-   --dry_run – this will be used with the --file directive in case we want to run the script but not insert into the database
-   -u – MySQL username
-   -p – MySQL password
-   -h – MySQL host
-   --help which will output the above list of directives with details

## Performing the various actions with examples

Connect to the php-app container (see above) to perform the various actions, examples listed below:

-   php user_upload.php (simple connect to the script)
-   php user_upload.php -u user -p password -h host
-   php user_upload.php --file=users.csv --dry_run
-   php user_upload.php --file=users.csv
-   php user_upload.php --create_table
-   php user_upload.php --help

## Assumptions

1. The script will be executed on an Ubuntu 18.04 instance
2. The PHP version to run the script is version 7.2.x
3. MySQL database server is already installed and is version 5.7
4. The databasse server should have a database called 'users_database'
5. The user running the script has adequate user priviliges
6. PHP script will be called – user_upload.php
7. CSV file will be called users.csv
8. To process the csv file use exact long option syntax --file=users.csv
