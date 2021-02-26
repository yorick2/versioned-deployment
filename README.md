[![Software License][icon-license]](LICENSE.md)
[![icon-repo-size]](#)
[![icon-code-size]](#)
[![icon-main-language]](#)
[![icon-php-version]](docker/dev/Dockerfile)
[![icon-commit-activity]](../../commits)
[![icon-last-commit-date]](../../commits)


[![icon-commit-version]](../../releases)
[![icon-latest-tag]](../../releases)

# Terms used 
|Term                |Definition                                                              |
| ------------------ |:----------------------------------------------------------------------:|
|Target system/server|The system/server you are deploying to from you server with this program|
|symlink             |A shortcut to a file from a different location|
|Target system/server|The system/server you are deploying to from you server with this program|
|Git cache           |A cache on a system used to store a repository so it dosnt need downloading multiple times. Used for speeding up git cloning dupliocate copies of a repository multiple times|
|phpunit             |An automatic command line testing tool used to test sections of code in isolation|
|Codeception         |An automatic command line testing tool. Performing acceptance testing as if with a mouse an keyboard in a web browser (e.g. Firefox, Chrome, Safari ..), using Selenium WebDriver|

# Version deployment proof of concept
An early proof of concept for deployment based on a versioned system.

Symlinks are heavily used, so this needs to be enabled on your target web server software (apache/nginx).

The system clones a new copy of the code into the releases folder, symlinks a current link to this folder and a previous
symlink to the last version.

For ease of use the deployment server doesnt need access to the git and uses a mirror on the destination server for
reference data and to speed up git clone on the destination server. 

## Setup your deployment target server
The deployment target server is the one you are going to deploy to
This is a rough process I have used in the past and tries to limit down time and chances of things going wrong. Note I
have not mentioned shared files and folders like any media folder, which will have to be dealt with too.
- Deploy into the folder above your web folder. If you server your website form /var/www/html then that would be
/var/www.
- Then when the deployment is complete check everything in the current symlink.
- Then in one command rename html to
html-old and create a symlink to the same location to current with the name of the web folder.
- If this works update your web server software (apache/nginx) to server your website from the 'current' folder and
remove your html symlink. Then after the next deployment the new release will be used. 
- If it didnt work remove the html symlink and rename the web folder back to html

# Deployed folder structure
|Folder|Use|
| ------------------ |:----------------------------------------------------------------------:|
|gitcache|This folder is clone of your git repository. It is used as a mirror when cloning the new release. This means we dont download a new git repository each time|
|current|This is a symlink to the current release folder used|
|previous|this is a symlink to the previous release folder used|
|release|uring each release a new folder is created here e.g. 2019-09-10_14-23-32|
|shared|Put files/folders that are shared between deployed versions and not in the git repository e.g. media folder, environment setting file|

## example structure
- current -> /var/www/releases/2019-09-11_09-05-50
- gitcache
- previous -> /var/www/releases/2019-09-10_14-29-51
- release 
    - 2019-09-10_14-23-32
    - 2019-09-10_14-27-32
    - 2019-09-10_14-29-51
    - 2019-09-10_14-53-48
    - 2019-09-11_09-05-50
- shared

# Setup
# Installation
Currently I only have a test/development version of docker working currently and isn't meant to be secure for production use as yet. There is more detail in the readme inside the docker folder.

``` sh
cd docker/dev;
docker-compose up;
```

Wait to see "site ready" in big letters in your terminal, then go to http://localhost:8000/ in your browser

## Setup for testing/development
Go to docker/dev and run 'sudo docker-compose up'. This will create a set of three docker containers and the project will be accessible at http://localhost:8080 in your web browser. A set of simple server tests can be found at http://localhost:8080/docker-tests/ and used to ensure the docker instances are working correctly.

The test user is:
- user: test@test.com
- password: password1

# Acceptance and Unit tests 
## Codeception tests
All code tests can be run on the version_deployment docker container using the command: "vendor/bin/codecept run -n"
This Will run the phpunit and codeception tests. The acceptance tests are run on the main database, so should not be run on production.

## Phpunit tests
The tests are run inside the docker container, using phpunit inside the vendor/bin folder. Phpunit is uses an sqlite database in memory for its tests. However, ensure the database has been migrated creating all the tables and has been seeded with data. If there is still an issue use "php artisan config:clear; php artisan cache:clear; composer dump-autoload"

## Debugging tests
RunPhpunitTest.php and RunCodeceptionTest.php files in the docker-tests folder run through the browser ie. at http://localhost:8000/docker-tests/RunPhpunitTest.php  and http://localhost:8000/docker-tests/RunCodeceptionTest.php. They return plain text so view the page source to view the responce. They can be used with xdebug as normal. This means not having to setup a command line xdebug, which can be problematic (especially with docker). Use view source in your browser to read the text. It is best used for only for single test files because running commands through the browser is slower that using phpunit/codeception directly.

## Requirements
- Docker
- Docker-compose

# Credits
- [Paul Millband][link-author]
- [All Contributors][link-contributors]

# License
The MIT License (MIT). Please see my [License File](LICENSE.md) for more information.

[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[icon-repo-size]: https://img.shields.io/github/repo-size/yorick2/versioned-deployment
[icon-code-size]: https://img.shields.io/github/languages/code-size/yorick2/versioned-deployment
[icon-main-language]: https://img.shields.io/github/languages/top/yorick2/versioned-deployment
[icon-commit-version]: https://img.shields.io/github/release/yorick2/versioned-deployment
[icon-latest-tag]: https://img.shields.io/github/tag-pre/yorick2/versioned-deployment
[icon-php-version]: https://img.shields.io/badge/PHP-7.2-blue
[icon-commit-activity]: https://img.shields.io/github/commit-activity/m/yorick2/versioned-deployment
[icon-last-commit-date]: https://img.shields.io/github/last-commit/yorick2/versioned-deployment
[link-author]: https://github.com/yorick2
[link-contributors]: ../../contributors
