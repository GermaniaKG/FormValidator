<?php
namespace tests;

use Germania\FormValidator\InputContainer;
use Germania\FormValidator\NotFoundException;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InputContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $sut = new InputContainer;

        $this->assertInstanceOf( ContainerInterface::class, $sut);
        $this->assertInstanceOf( \ArrayAccess::class, $sut);
    }


    public function testContainerExceptions()
    {
        $sut = new InputContainer;

        try {
            $t = $sut->get('foo');
        }
        catch (\Exception $e) {
            $this->assertInstanceOf( NotFoundException::class, $e);
            $this->assertInstanceOf( NotFoundExceptionInterface::class, $e);
            return;
        }
        $this->fail( "No exception thrown?!" );
    }


    public function testNullValues()
    {
        $sut = new InputContainer;

        $this->assertNull( $sut->offsetGet('foo') );
        $this->assertNull( $sut['foo'] );

        $this->assertFalse($sut->has("foo"));
        $this->assertFalse($sut->offsetExists("foo"));
    }


    public function testExistingValues()
    {
        $key = "foo";

        $sut = new InputContainer( [
            $key => "bar"
        ]);

        $this->assertTrue($sut->has( $key ));
        $this->assertTrue($sut->offsetExists( $key ));

        $r1 = $sut->offsetGet( $key );
        $r2 = $sut[ $key ];
        $r3 = $sut->get( $key );

        $this->assertEquals( $r1, $r2 );
        $this->assertEquals( $r2, $r3 );

    }
}
