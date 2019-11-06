@echo off
if not exist config.php ( 
  echo please create config.php as config.php.sample
  exit
)
./vendor/bin/phpunit --bootstrap vendor/autoload.php --bootstrap config.php --testdox tests
