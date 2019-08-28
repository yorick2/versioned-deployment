[![Software License][icon-license]](LICENSE.md)
[![icon-repo-size]](#)
[![icon-code-size]](#)
[![icon-main-language]](#)
[![icon-php-version]](docker/dev/Dockerfile)
[![icon-commit-activity]](../../commits)
[![icon-last-commit-date]](../../commits)


[![icon-commit-version]](../../releases)
[![icon-latest-tag]](../../releases)

# Version deployment proof of concept
An early proof of concept for deployment based on a versioned system.

Symlinks are heavily used, so this needs to be enabled on your web server software (apache/nginx).

The system clones a new copy of the code into the releases folder, symlinks a current link to this folder and a previous
symlink to the last version.

For ease of use the deployment server doesnt need access to the git and uses a mirror on the destination server for
reference data and to speed up git clone on the destination server. 

## Starting to use this system
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

# Setup
Currently I only have a test/development version of docker working currently and isn't meant to be secure for production use as yet. There is more detail in the readme inside the docker folder.

## Setup for testing/development
Go to docker/dev and run 'sudo docker-compose up'. This will create a set of three docker containers and the project will be accessible at http://localhost:8080 in your web browser. A set of simple server tests can be found at http://localhost:8080/docker-tests/ and used to ensure the docker instances are working correctly. The test user is test@test.com and the password is password1.
 
## Requirements
Docker
Docker-compose


# Credits

- [Paul Millband][link-author]
- [All Contributors][link-contributors]

 License

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
