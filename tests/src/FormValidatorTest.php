<?php
namespace tests;

use Germania\FormValidator\FormValidator;
use Germania\FormValidator\InputContainer;
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
        $this->assertInstanceOf( \ArrayAccess::class, $filtered_input );
        $this->assertInstanceOf( ContainerInterface::class, $filtered_input );
        $this->assertInstanceOf( InputContainer::class, $filtered_input );

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


    /**
     * @dataProvider provideData
     */
    public function testCustomReturnContainers( $required, $optional, $raw_input, $expected_submitted, $expected_valid )
    {
        $factory = function( $filtered_input ) {
            return new \ArrayObject( $filtered_input );
        };

        // Setup 1: factory with Ctor
        $sut = new FormValidator( $required, $optional, $factory );
        $container1 = $sut( $raw_input );
        $this->assertInstanceOf( \ArrayObject::class, $container1 );

        // Setup 2: factory per Call
        $sut = new FormValidator( $required, $optional);
        $container2 = $sut( $raw_input );
        $this->assertInstanceOf( InputContainer::class, $container2 );

        $container3 = $sut( $raw_input, $factory );
        $this->assertInstanceOf( \ArrayObject::class, $container3 );
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

        $paramlist['Submitted and valid'] = [ $required, $optional, $raw_input, true, true ];

        // Tweak: E-Mail malformed
        $raw_input['user_email'] = "something_weird";
        $paramlist['Submitted and invalid'] = [ $required, $optional, $raw_input, true, false ];


        // Tweak: Empty array (like no user input at all)
        $raw_input = array();
        $paramlist['Not submitted'] = [ $required, $optional, $raw_input, false, false ];


        return $paramlist;
    }

}

