<?php

use PHPUnit\Framework\TestCase;

final class AcImportOutputTest extends TestCase
{
    protected $appointment;
    public function setUp(): void
    {
        $this->appointment = array (
  'id' => 303327525,
  'firstName' => 'Andreína',
  'lastName' => 'Quiñones',
  'phone' => '+584127097049',
  'email' => 'andreinaqr91@gmail.com',
  'date' => 'August 26, 2019',
  'time' => '14:00',
  'endTime' => '15:30',
  'dateCreated' => 'July 25, 2019',
  'datetimeCreated' => '2019-07-24T21:21:28-0500',
  'datetime' => '2019-08-26T14:00:00+0200',
  'price' => '0.00',
  'priceSold' => '0.00',
  'paid' => 'no',
  'amountPaid' => '0.00',
  'type' => 'Visita Vestidos de Novia | Appointment for Bridal Gowns',
  'appointmentTypeID' => 5459791,
  'classID' => NULL,
  'addonIDs' => 
  array (
  ),
  'category' => '',
  'duration' => '90',
  'calendar' => 'Weddingland Barcelona',
  'calendarID' => 1863916,
  'certificate' => NULL,
  'confirmationPage' => 'https://www.acuityscheduling.com/schedule.php?action=appt&owner=15051967&id%5B%5D=6debe207a4a45515f5c88bd4cbc5ea6f',
  'location' => 'Weddingland Barcelona',
  'notes' => '',
  'timezone' => 'Europe/Madrid',
  'calendarTimezone' => 'Europe/Madrid',
  'canceled' => false,
  'canClientCancel' => true,
  'canClientReschedule' => true,
  'labels' => NULL,
  'forms' => 
  array (
    0 => 
    array (
      'id' => 843435,
      'name' => '',
      'values' => 
      array (
        0 => 
        array (
          'id' => 907107661,
          'fieldID' => 4420307,
          'value' => '1',
          'name' => 'Numero de acompañantes | Number of attendats',
        ),
        1 => 
        array (
          'id' => 907107662,
          'fieldID' => 4312725,
          'value' => '12/30/2019',
          'name' => 'Fecha de evento',
        ),
        2 => 
        array (
          'id' => 907107663,
          'fieldID' => 4361555,
          'value' => 'Emocionada de ir a esta cita con ustedes!',
          'name' => 'Comenario | Comment',
        ),
      ),
    ),
  ),
  'formsText' => 'Name: Andreína Quiñones
Phone: +584127097049
Email: andreinaqr91@gmail.com

Location
============
Weddingland Barcelona



============
Numero de acompañantes | Number of attendats: 1

Fecha de evento: 12/30/2019

Comenario | Comment: Emocionada de ir a esta cita con ustedes!

',
  'isVerified' => false,
  'scheduledBy' => NULL,
);

				$this->assertNotEmpty( $this->appointment['forms'][0]['values']);


    }

    public function testCanPrintAppointment(): void
    {
				# var_export($this->appointment['forms'][0]['values']);

        $this->assertEquals(
            [ 303327525,"Andreína Quiñones","andreinaqr91@gmail.com","+584127097049","","14:00","90","Visita Vestidos de Novia | Appointment for Bridal Gowns",
							"30/12/2019","Emocionada de ir a esta cita con ustedes!","26/08/2019 14:00" ], 
            AcImport::appointmentToArray2($this->appointment)
        );
    }

    public function testConvertAppointmentToString(): void
    {
				# var_export($this->appointment['forms'][0]['values']);
        mb_convert_encoding( "", 'Windows-1252' );
        $this->assertEquals(
            [ 
"26/08/2019 14:00","26/08/2019 15:30",
mb_convert_encoding( "Andreína", 'Windows-1252' ),	
mb_convert_encoding( "Quiñones", 'Windows-1252' ),		
mb_convert_encoding( "ANDREÍNA QUIÑONES", 'Windows-1252' ),
"+584127097049","andreinaqr91@gmail.com","Visita Vestidos de Novia | Appointment for Bridal Gowns","Weddingland Barcelona",
"26/08/2019","no","0.00","","","July 25, 2019","","","1","30/12/2019","Emocionada de ir a esta cita con ustedes!","303327525"

#303327525,"Andreína Quiñones","andreinaqr91@gmail.com","+584127097049","","14:00","90","Visita Vestidos de Novia | Appointment for Bridal Gowns",
#							"30/12/2019","Emocionada de ir a esta cita con ustedes!","26/08/2019 14:00" 
], 
            explode("\t",AcImport::appointmentToString($this->appointment))
        );
    }

}


