<?php

use PHPUnit\Framework\TestCase;

final class AcImportTest extends TestCase
{
/*    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(
            Email::class,
            Email::fromString('user@example.com')
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Email::fromString('invalid');
    }
*/

    private static $forms = Array(
            Array
                (
                    'id' => 843435,
                    'name' => '',
                    'values' => Array(
                            Array(
                                    'id' => 895303712,
                                    'fieldID' => 4420307,
                                    'value' => '',
                                    'name' => 'Numero de acompañantes | Number of attendats'
                                ),

                            Array
                                (
                                    'id' => 895303713,
                                    'fieldID' => 4312725,
                                    'value' => '12 de diciembre 2018',
                                    'name' => 'Fecha de evento'
                                ),

                            Array
                                (
                                    'id' => 895303714,
                                    'fieldID' => 4361555,
                                    'value' => 'test comment',
                                    'name' => 'Comenario | Comment'
                                )

                        )

                )

        );

    public function testCanFindCommentInForms(): void
    {
        $this->assertEquals(
            'test comment',
            AcImport::getFieldValue(AcImportTest::$forms, 'Comenario | Comment', 4361555)
        );
        $this->assertEquals(
            '12 de diciembre 2018',
            AcImport::getFieldValue(AcImportTest::$forms, 'Fecha de evento | Date of event', 4312725)
        );
    }


    public function testCanConvertSpanishMonthToN(): void
    {
        $this->assertEquals(
            11,
            AcImport::esMonthToN('noviembre')
        );
        $this->assertEquals(
            0,
            AcImport::esMonthToN('asdasdsa')
        );

        $this->assertEquals(
            0,
            AcImport::esMonthToN('march')
        );
        
    } 

    public function testFindsNextYearProperly(): void
    {

        $date1 = date_create("now");
        $date1ok = $date1->add(new DateInterval("P1Y"));
        $date2 = $date1->sub(new DateInterval("P10Y"));
        $date3 = $date1->add(new DateInterval("P1D"));
        $date4 = $date1->sub(new DateInterval("P1D"));
        $date4ok = $date4->add(new DateInterval("P1Y"));

        $this->assertEquals( $date1ok, AcImport::forceToFutureYear( $date1 ) );
        $this->assertEquals( $date1ok, AcImport::forceToFutureYear( $date2 ) );
        $this->assertEquals( $date3, AcImport::forceToFutureYear( $date3 ) );
        $this->assertEquals( $date4ok, AcImport::forceToFutureYear( $date4 ) );
    }

    public function testCanConvertLabelsToString(): void
    {
        $labels = [
		    array (
		      'id' => 33372,
		      'name' => 'Checked In',
		      'color' => 'green',
		    ),
		    array (
		      'id' => 33372,
		      'name' => 'Checked Out',
		      'color' => 'green',
		    ),

 ];

        $this->assertEquals(
            'Checked In,Checked Out',
            AcImport::labelsAsString( $labels )
        );
    }

    public function testCanReadTextSpanishDates(): void
    {
        $this->assertEquals(
            date_create("2019-12-11"),
            AcImport::readDate('11 de diciembre 2019')
        );
        $this->assertEquals(
            date_create("2019-12-11"),
            AcImport::readDate('11/12/2019'),
            "DD/MM/YYYY case should be processed well"
        );
        $this->assertEquals(
            date_create("2019-09-21"),
            AcImport::readDate('21/09/2019')
        );
        $this->assertEquals(
            AcImport::forceToFutureYear( date_create("2019-09-28") ),
            AcImport::readDate('28 de setiembre')
        );

    }
    public function testFailsToParseOnProperCases(): void
    {
        $this->assertEquals(
            '',
            AcImport::readDate('2020')
        );

        $this->assertEquals(
            '',
            AcImport::readDate('')
        );

    }

    public function testCanParseAllDatesOfList(): void
    {
        $handle = fopen('C:\work\wedding\api\tests\acuityDates.txt', "r");
        if ($handle) {
            $res = Array();
            $errors = Array();
            $ok = 0; $line_num = 0;
		        while (($line = fgets($handle)) !== false) {
                $line_num += 1;
		            $ar = explode("\t", $line);
		            $date = $ar[0];
		            $parsed = AcImport::readDate( $date );
		            array_push( $res, [ $line_num, $date, $parsed ] );
		            if ($parsed instanceOf DateTime) { $ok+= 1; } else { array_push( $errors, [ $line_num, $date, $parsed ] ); }
#		            var_dump($date);
#                var_dump( AcImport::readDate( $date ) );
#                var_dump( AcImport::readDate( "21/09/2019" ) );
#                $this->assertEquals( "21/09/2019", $date );
		        }
	        	fclose($handle);
	        	$handle = fopen('C:\work\wedding\api\tests\acuityDates.err', "w");
            foreach ($errors as $fields) {
                fputcsv($handle, $fields);
            }
	        	fclose($handle);
	        	# var_dump($errors);
            $this->assertTrue( sizeof($errors)<=113, "Failed to parse ".sizeof($errors)." lines of ".$line_num." in test dates" );

	      } else {
     				$this->assertTrue(False,"Can not open text file");
	      }
    }


}


