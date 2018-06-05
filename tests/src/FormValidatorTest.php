<?php
namespace tests;

use Germania\FormValidator\FormValidator;
use Psr\Container\ContainerInterface;

class FormValidatorTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider provideData
     */
    public function testUsage( $required, $optional, $raw_input, $expected_submitted, $expected_valid )
    {
        // Setup
        $sut = new FormValidator( $required, $optional );

        // Uses PHP's filter_var_array internally
        $filtered_input = $sut( $raw_input );

        // At least one required field valid?
        $this->assertSame( $sut->isSubmitted(), $expected_submitted );

        // All required fields valid?
        $this->assertSame( $sut->isValid(), $expected_valid );

    }

    /**
     * @dataProvider provideData
     */
    public function testAddingAndRemovingFields( $required, $optional, $raw_input, $expected_submitted, $expected_valid )
    {
        // Setup
        $sut = new FormValidator( $required, $optional );

        $field = 'foo';

        $sut->addRequired($field, FILTER_SANITIZE_STRING);
        $this->assertTrue( array_key_exists($field, $sut->required_fields));
        $this->assertFalse( array_key_exists($field, $sut->optional_fields));

        $sut->addOptional($field, FILTER_SANITIZE_STRING);
        $this->assertTrue( array_key_exists($field, $sut->optional_fields));
        $this->assertFalse( array_key_exists($field, $sut->required_fields));
    }




    public function provideData() {

        $paramlist = array();

        $required = [
            "send_button"     =>   FILTER_VALIDATE_BOOLEAN,
            "user_email"      =>   FILTER_VALIDATE_EMAIL,
            "user_login_name" =>   FILTER_SANITIZE_STRING
        ];

        $optional = [
            "family_name"     =>   FILTER_SANITIZE_STRING,
            "first_name"      =>   FILTER_SANITIZE_STRING
        ];

        $raw_input = [
            "send_button"     =>   "true",
            "user_email"      =>   "me@test.com",
            "user_login_name" =>   "myloginname",
            "family_name"     =>   "Doe",
            "first_name"      =>   "John"
        ];

        array_push($paramlist, [ $required, $optional, $raw_input, true, true ]);

        // Tweak: E-Mail malformed
        $raw_input['user_email'] = "something_weird";
        array_push($paramlist, [ $required, $optional, $raw_input, true, false ]);

        // Tweak: Empty array (like no user input at all)
        $raw_input = array();
        array_push($paramlist, [ $required, $optional, $raw_input, false, false ]);

        return $paramlist;
    }

}

