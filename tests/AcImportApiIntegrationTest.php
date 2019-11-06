<?php

use PHPUnit\Framework\TestCase;

class AcImportApiIntegrationTest extends TestCase
{
    protected $acuity = '';
    public function setUp(): void
    {
				$this->acuity = AcImport::connect(array(
				  'userId' => ACUITY_USER_ID,
				  'apiKey' => ACUITY_API_KEY
				));

		    $this->assertInstanceOf( 'AcuityScheduling', $this->acuity );
    }

    public function testCanGetAppointment(): void
    {
        #  $this->markTestSkipped(); return; 
        $appointment = AcImport::getAppointmentById( "303327525" );

			  $this->assertEquals( '+584127097049', $appointment['phone'] );
				# var_export($appointment);
    }



    public function testCanTransformToCSV(): void
    {
       $app = AcImport::getAppointmentById( "313261071" );
       $res = AcImport::appointmentToArray( $app );
       $this->assertEquals( str_replace("\t","\n","02/09/2019 10:15	02/09/2019 11:45	SELINA	ESTROBLER MENDEZ	SELINA ESTROBLER MENDEZ	+417909401735	selina.estrobler@gmail.com	Visita Vestidos de Novia | Appointment for Bridal Gowns	Weddingland Barcelona	02/09/2019	no	0.00		HA VENIDO CON MARTINA	September 2, 2019	Completed	info@weddinglandbcn.es				313261071"), join("\n",$res) );
    }

    public function testCanGetPeriodAppointments(): void
    {
        $apps = AcImport::getAppointmentsFromTo("2019-08-31", "2019-08-31");
        # var_export( $apps );
		    # $this->assertInstanceOf( 'Array', $apps );

        $this->assertEquals( 20, sizeof($apps)  );
        # $this->assertEquals( 306037629, $apps[17]['id']);
        $this->assertEquals( 310375042, $apps[15]['id']);
        $this->assertEquals( "Cabañas", $apps[15]['lastName']);
        $this->assertEquals( "San Martín", $apps[13]['lastName']);
        # var_export(  );
        $this->assertEquals(  
						array (
						  0 => '31/08/2019 16:00',
						  1 => '31/08/2019 16:15',
						  2 => 'Elena',
						  3 => 'San Martín',
						  4 => 'ELENA SAN MARTÍN',
						  5 => '+34653731235',
						  6 => 'esanmartin94@gmail.com',
						  7 => 'Recogida',
						  8 => 'Weddingland Barcelona',
						  9 => '31/08/2019',
						  10 => 'no',
						  11 => '0.00',
						  12 => NULL,
						  13 => 'lavarrrrrrr; vestido y velo',
						  14 => 'June 22, 2019',
						  15 => '',
						  16 => '',
						  17 => '',
						  18 => '',
						  19 => '',
						  20 => 295834471,
						), AcImport::appointmentToArray( $apps[13] )
				);

    }
}


