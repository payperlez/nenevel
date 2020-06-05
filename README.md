# nenevel
 A PHP minimal framework which allows you to control every bit of it. Build by Nene Paid Ademang (In loving memory, 1991 - 2020). Modified for production by Team Payperlez.

# HOW TO USE

Install composer globally. Do the following on a Linux. Visit https://getcomposer.org/doc/00-intro.md for windows.


```bash

$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
$ mv composer.phar /usr/local/bin/composer

```
# Install composer on MacBook
```bash
$ brew install composer

- After it you should see something like that:
==> Installing homebrew/php/composer
==> Downloading https://homebrew.bintray.com/bottles-php/composer-1.3.2.el_capitan.bottle.tar.gz
######################################################################## 100.0%
==> Pouring composer-1.3.2.el_capitan.bottle.tar.gz
 /usr/local/Cellar/composer/1.3.2: 5 files, 1.7Mb
```
- Let’s test it, try to run the command below:
```bash
composer --version
```
If you saw a number of version then everything has gone fine.

- Create a new project using composer.

```bash

$ composer create-project nenevel/framework [project_name]

```
