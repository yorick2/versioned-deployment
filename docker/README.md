# docker composer
## startup the docker composition
docker-compose up
## rebuild a docker-compose images
To rebuild this image you must use `docker-compose build`

## ssh into a container
sudo docker exec -it <<container name>> bash

## Stop all running containers
Warning: This will stop all your containers.
docker stop $(docker ps -a -q)

## Delete all containers
Warning: This will destroy all your images and containers. It will not be possible to restore them!
docker rm $(docker ps -a -q)

## Delete all images
Warning: This will destroy all your images and containers. It will not be possible to restore them!
docker rmi $(docker images -q)

# access mysql 
## from the host machine (external to the docker containers)
mysql -h<<image ip>> --port=9906 -udevuser -p
or 
mysql -h<<image ip>> --port=9906 -uroot -p

## from inside a container
You cannot run mysql from inside another container but can connect to mysql from inside another container. The ame of the container is
used for the url. The other details are
host: db;
user: devpass
password: devtest
port: 3306 

test the connection
php /code/test_mysql_connection.php

# tests
## connection tests
Included are some test files to test that php, apache and mysql connections work. The list of tests are accessable from. The port can be found in the docker-compose.yml for the example. 
http://localhost:<<port>>/docker-tests/

e.g
http://localhost:8080/docker-tests/

# run website in browser
http://<docker-host-ip-address>:8080
 
# notes
https://docs.docker.com/compose/production/

