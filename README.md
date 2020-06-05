# nenevel
 A PHP minimal framework which allows you to control every bit of it. Build by Nene Paid Ademang (In loving memory, 1991 - 2020). Modified for production by Team Payperlez.

# HOW TO USE

Install composer globally. Do the following on a Linux. Visit https://getcomposer.org/doc/00-intro.md for windows.

<code>
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"<br>
$ php -r "if (hash_file('SHA384', 'composer-setup.php') ==='e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae')
 <br>{ echo 'Installer verified'; } <br><br> else { echo 'Installer corrupt'; unlink('composer-setup.php'); 
}
echo PHP_EOL;"
$ php composer-setup.php <br><br>
$ php -r "unlink('composer-setup.php');"<br><br>
$ mv composer.phar /usr/local/bin/composer
</code>

Create a new project using composer.<br><br>
<code>
$ composer create-project diy/framework [project_name]
</code>
