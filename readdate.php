<?php
require_once('vendor/autoload.php');

# $file = fopen('php://stdin' );
while (!feof(STDIN)) {
    $f = stream_get_line(STDIN, 1000000, "\r\n");

# foreach (new SplFileObject($file) as $f) {

    $l = rtrim($f);
    $date = AcImport::readDate($l);
    if ($date InstanceOf DateTime) {
      echo date_format( $date, "Y-m-d")."\r\n";
    } else {
      echo $l."\r\n";
    }
}