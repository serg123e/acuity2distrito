<?php
require_once('vendor/autoload.php');
require_once('config.php');

AcImport::connect(array(
				  'userId' => ACUITY_USER_ID,
				  'apiKey' => ACUITY_API_KEY
				));


$today = date('Y-m-d');
$nextweek = date('Y-m-d', strtotime("+7 days"));
#echo $today;
#echo $nextweek;
$apps = AcImport::getAppointmentsFromTo( $today, $nextweek );

echo AcImport::appointmentCSVHeader()."\n";

foreach($apps as $ap) {
  if ( # $ap['type'] == 'Visita Vestidos de Novia | Appointment for Bridal Gowns' or 
      $ap['appointmentTypeID'] == '5501175' or
      # $ap['type'] == 'Visita Vestidos de Fiesta | Appointment for Cocktail Dresses' or 
      $ap['appointmentTypeID'] == '5459791'
     ) { 
    echo AcImport::appointmentToString($ap)."\n";
  }
}


# $file = fopen('php://stdin' );
#while (!feof(STDIN)) {
#    $f = stream_get_line(STDIN, 1000000, "\r\n");

# foreach (new SplFileObject($file) as $f) {

#    $l = rtrim($f);
