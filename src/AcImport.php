<?php

class AcImport {

		private  const  CSV_MULTIPLE_VALUE_SEPARATOR = '; ';
		private  const  CSV_FIELD_SEPARATOR = "\t";

    public static $acuity = '';

    public static function connect( $params ) {
				self::$acuity = new AcuityScheduling($params);
				return self::$acuity;
    }

		public static function getFieldValue($forms,$name,$fieldID) {
		    #var_export( $forms[0]['values'] );
        $result = '';
		    if (sizeof($forms) >= 1) {
			    foreach($forms[0]['values'] as $form) {
			        # print_r($form);
			        if ($form['name'] == $name or $form['fieldID'] == $fieldID) {
									$result = $form['value'];
                  break;
			        }
			    }
        } else {
						$result = '';
        }
        return $result;
		}

		public static function getComentarioEvento($forms) {
		    return self::getFieldValue( $forms, 'Comenario | Comment', 4361555);
		}
    
		public static function getFechaEvento($forms) {
		    return self::getFieldValue( $forms, 'Fecha de evento', 4312725);
		}
		public static function getAttendants($forms) {
		    return self::getFieldValue( $forms, 'Numero de acompañantes | Number of attendats', 4420307);
		}


		public static function tryDateParseFromFormat($format,$date) {
    		$dt = date_parse_from_format($format, $date);
        if ($dt['error_count']>0) { return Array(); } else { return $dt; }
    }

		public static function esMonthToN($str) {
		    switch (strtolower($str)){
            case "enero":      return 1;
            case "febrero":    return 2;
            case "marzo":      return 3;
            case "abril":      return 4;
            case "mayo":       return 5;
            case "junio":      return 6;
            case "julio":      return 7;
            case "agosto":     return 8;
            case "septiembre": return 9;
            case "octubre":    return 10;
            case "noviembre":  return 11;
            case "diciembre":  return 12;
            default:  return 0;
        }
    }

    public static function forceToFutureYear( $datetime ) {
        $now = date_create("now");
        while ($datetime < $now) {
            $datetime->add(new DateInterval('P1Y'));
        }
        return $datetime;
    }

		public static function readDate($str) {

		    $str = preg_replace('#setiembre|setembro|septembre#i', 'septiembre', $str );
        $str = preg_replace('#maig#i', 'mayo', $str );
        $str = preg_replace('#juliol#i', 'julio', $str );
        $str = preg_replace('#de juny#i', 'de junio', $str );

        if (preg_match('#^(\d+)[\s\/](?:de )?(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)[\s\/](?:de |del )?(\d{4})$#i', $str, $dt) and checkdate(self::esMonthToN($dt[2]), $dt[1], $dt[3])) {
            return( date_create( $dt[3]."-". self::esMonthToN($dt[2])."-". ($dt[1])) );
        } elseif (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $str, $dt) and checkdate($dt[2], $dt[1], $dt[3])) {
            return( date_create( $dt[3]."-".$dt[2]."-".$dt[1]) );
        } elseif (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{2})$#', $str, $dt) and checkdate($dt[2], $dt[1], "20".$dt[3])) {
            return( date_create( "20".$dt[3]."-".$dt[2]."-".$dt[1]) );
        } elseif (preg_match('#^(\d+) (?:de )?(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre) (?:de |del )?(\d{2})$#i', $str, $dt) and checkdate(self::esMonthToN($dt[2]), $dt[1], 2000+$dt[3])) {
            return( date_create( "20".$dt[3]."-". self::esMonthToN($dt[2])."-". ($dt[1])) );

        } elseif (preg_match('#^(\d{2})\.(\d{2}).(\d{4})$#', $str, $dt) and checkdate($dt[2], $dt[1], $dt[3])) {
            return( date_create( $dt[3]."-".$dt[2]."-".$dt[1]) );
        } elseif (preg_match('#^(\d{1,2})\.(\d{1,2})\.(\d{2})$#', $str, $dt) and checkdate($dt[2], $dt[1], "20".$dt[3])) {
            return( date_create( "20".$dt[3]."-".$dt[2]."-".$dt[1]) );

        } elseif (preg_match('#^(\d{1,2}),(\d{1,2}),(\d{4})$#', $str, $dt) and checkdate($dt[2], $dt[1], $dt[3])) {
            return( date_create( $dt[3]."-".$dt[2]."-".$dt[1]) );


        } elseif (preg_match('#^(\d{2})[,\/](\d{2})$#', $str, $dt) and checkdate($dt[2], $dt[1], 2020)) {
            return( self::forceToFutureYear( date_create( "2019-".$dt[2]."-".$dt[1] )) );

        } elseif (preg_match('#^(\d{2})[,\.\/](\d{4})$#', $str, $dt) and checkdate($dt[1], 1, $dt[2])) {
            return(  date_create( $dt[2]."-".($dt[1])."-01") );
        } elseif (preg_match('#^(\d{2})\.(\d{2})$#', $str, $dt) and checkdate($dt[2], $dt[1], 2020)) {
            return( self::forceToFutureYear( date_create( "2019-".$dt[2]."-".$dt[1] )) );

        } elseif (preg_match('#^(\d{1,2}) (?:de |del )?(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)$#i', $str, $dt)) {
            return( self::forceToFutureYear( date_create( "2019-".self::esMonthToN($dt[2])."-".$dt[1] )) );

        } elseif (preg_match('#^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre) (\d{4})$#i', $str, $dt)) {
            return( date_create( $dt[2]."-".self::esMonthToN($dt[1])."-01" ) );

        } elseif (preg_match('#^(enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre)$#i', $str, $dt)) {
            return( self::forceToFutureYear( date_create( "2019-".self::esMonthToN($dt[1])."-01" )) );


        } else { $dt = date_parse($str);
            if ($dt['error_count'] > 0) { 
                return ''; 
            } else { 
                return date_create( $dt['year']."-".$dt['month']."-".$dt['day'] );
            }
        }
		}


    public static function appointmentToString( $appointment ) {
        return mb_convert_encoding( implode( self::CSV_FIELD_SEPARATOR, self::appointmentToArray( $appointment ) ), 'Windows-1252' );
    }

    public static function appointmentCSVHeader() {
        return join(self::CSV_FIELD_SEPARATOR, [  "Start Time","End Time","First Name","Last Name","Full Name","Phone","Email","Type","Calendar","Visit date","Paid?","Amount Paid Online",
        "Certificate Code","Notes",
        "Date Scheduled","Label","Scheduled By","Numero de acompañantes | Number of attendats","Fecha de evento | Date of event (DD/MM/YYYY)","Comenario | Comment","Appointment ID" ] );
/*
Start Time	End Time	First Name	Last Name	Full Name	Phone	Email	Type	Calendar	Visit date	Paid?	Amount Paid Online	Certificate Code	Notes	Date Scheduled	Label	Scheduled By	Numero de acompañantes | Number of attendats	Fecha de evento | Date of event (DD/MM/YYYY)	Comenario | Comment	Appointment ID
September 2, 2019 10:15	September 2, 2019 11:45	SELINA	ESTROBLER MENDEZ	SELINA ESTROBLER MENDEZ	+417909401735	selina.estrobler@gmail.com	Visita Vestidos de Novia | Appointment for Bridal Gowns	Weddingland Barcelona	02/09/2019	no	0.00		HA VENIDO CON MARTINA	02/09/2019	Completed	info@weddinglandbcn.es				313261071
September 3, 2019 15:30	September 3, 2019 17:00	Kelly	Froats	Kelly Froats	+13307661309	kellifroats@gmail.com	Visita Vestidos de Novia | Appointment for Bridal Gowns	Weddingland Barcelona	03/09/2019	no	0.00			03/09/2019	Checked In	info@weddinglandbcn.es				313487486
*/
    }

    public static function asOneLine( $string ) {
      return preg_replace('/\s*[\r\n]+\s*/', self::CSV_MULTIPLE_VALUE_SEPARATOR, trim($string));
    }

    public static function labelsAsString( $labels ) {
      $res = [];
      if (is_array($labels)) {
	      forEach($labels as $label) {
	        array_push($res, $label['name']);
	      }
      }
      return join(",",$res);
    }

    public static function appointmentToArray( $appointment ) {
        # $datetime = DateTime::createFromFormat('d/m/Y H:i', $appointment['datetime']);
        $datetime = DateTime::createFromFormat('Y-m-d\TH:i:sO', $appointment['datetime']);
        $datetime_end = DateTime::createFromFormat('Y-m-d\TH:i:sO', $appointment['datetime']);;
				$datetime_end->add(new DateInterval('PT' . $appointment['duration'] . 'M'));

				$form_attendants = self::getAttendants( $appointment['forms'] );
				$date_of_event = self::readDate( self::getFechaEvento( $appointment['forms'] ) );
				if (is_a($date_of_event, 'DateTime')) {
					$form_date_of_event = date_format( $date_of_event, "d/m/Y");
        } else { 
					$form_date_of_event = ''; 
				}
				$form_comment = self::getComentarioEvento( $appointment['forms'] );
         # '', # $appointment['scheduledBy'], # no scheduledBy in result, TODO: ask Acuity about scheduledBy
        $res = [ date_format( $datetime ,"d/m/Y H:i"),
                 date_format( $datetime_end ,"d/m/Y H:i"),
                 $appointment['firstName'],
                 $appointment['lastName'],
                 mb_strtoupper( $appointment['firstName'].' '. $appointment['lastName'] ),
                 $appointment['phone'],
                 $appointment['email'],
                 $appointment['type'],
                 $appointment['calendar'],
                 date_format( $datetime ,"d/m/Y"),
                 $appointment['paid'],
                 $appointment['amountPaid'],
                 $appointment['certificate'],
                 self::asOneLine( $appointment['notes'] ),
                 $appointment['dateCreated'],
                 self::labelsAsString( $appointment['labels'] ),
                 array_key_exists('scheduledBy', $appointment ) ? $appointment['scheduledBy'] : '', # no scheduledBy in result for mass req, TODO: ask Acuity about scheduledBy
                 $form_attendants,
                 $form_date_of_event,
                 self::asOneLine( $form_comment ),
                 $appointment['id']
#                 $app
               ];

        return $res;
    }

    public static function getAppointmentById( $id ) {
				return self::$acuity->request("/appointments/$id", array(
						'query' => array(
							'max' => 1
						)
					));
    }

    public static function getAppointmentsFromTo( $from, $to ) {
        $appointments = self::$acuity->request("/appointments", array(
						'query' => array(
							'direction' => 'ASC',
							'minDate' => $from,
							'maxDate' => $to
						)
					));
				return $appointments;

    }

    public static function appointmentToArray2( $appointment ) {
        $datetime = DateTime::createFromFormat('Y-m-d\TH:i:sO', $appointment['datetime']);
			  $res = [ $appointment['id'], 
                 $appointment['firstName']." ".$appointment['lastName'],
                 $appointment['email'],
                 $appointment['phone'] ];

			  if (sizeof($appointment['forms'])>=3) { # normal user entry
						array_push( $res, date_format( $datetime ,"d/m/Y H:i") );
			  } else {
						array_push( $res, "" );
			  }
			  array_push( $res, $appointment['time'] );
			  array_push( $res, $appointment['duration'] );
			  array_push( $res, $appointment['type'] );
			  array_push( $res, date_format( self::readDate( self::getFechaEvento( $appointment['forms'] )), "d/m/Y") );
			  array_push( $res, self::getComentarioEvento( $appointment['forms'] ) );

			  if (sizeof($appointment['forms'])<3) { # manual entry?
						array_push( $res, date_format( $datetime, "d/m/Y H:i") );
			  } else {
						array_push( $res, "" );
			  }

			  return $res;
    }

}