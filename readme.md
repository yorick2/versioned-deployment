[![Software License][icon-license]](LICENSE.md)
![icon-repo-size]
![icon-main-language]
![icon-php-version]
![icon-commit-activity]
![icon-last-commit-date]


![icon-commit-version]
![icon-latest-tag]

# Version deployment proof of concept
A proof of concept for a deployment system, based on a versioned system.

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

## Credits

- [Paul Millband][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see my [License File](LICENSE.md) for more information.

[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[icon-repo-size]: https://img.shields.io/github/repo-size/yorick2/versioned-deployment
[icon-main-language]: https://img.shields.io/github/languages/top/yorick2/versioned-deployment
[icon-commit-version]: https://img.shields.io/github/release/yorick2/versioned-deployment
[icon-latest-tag]: https://img.shields.io/github/tag-pre/yorick2/versioned-deployment
[icon-php-version]: https://img.shields.io/badge/PHP-7.1.3-blue
[icon-commit-activity]: https://img.shields.io/github/commit-activity/m/yorick2/versioned-deployment
[icon-last-commit-date]: https://img.shields.io/github/last-commit/yorick2/versioned-deployment
[link-author]: https://github.com/yorick2
[link-contributors]: ../../contributors
