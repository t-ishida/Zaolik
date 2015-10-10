<?php

namespace Zaolik;
class DIContainerTest extends \PHPUnit_Framework_TestCase 
{
    private $target = null;
    public function setUp () 
    {
        $this->target = DIContainer::getInstance();
        $this->target->clear();
        $this->target->setFlyWeight ('test', function () {
            $obj = new \stdClass();
            $obj->field = 1;
            return $obj;
        });
        $this->target->setNew ('test', function ($a, $b, $c) {
            $obj = new \stdClass();
            $obj->field1 = $a;
            $obj->field2 = $b;
            $obj->field3 = $c;
            return $obj;
        });
    }
    
    public function testFlyWeight() 
    {
        $obj1 = $this->target->getFlyWieght('test');
        $obj2 = $this->target->getFlyWieght('test');
        $this->assertSame($obj1, $obj2);
    }

    public function testNewInstance() 
    {
        $obj1 = $this->target->getNewInstance('test', array('a', 'b', 'c'));
        $obj2 = $this->target->getNewInstance('test', array('a', 'b', 'c'));
        $this->assertNotSame($obj1, $obj2);
        $this->assertEquals($obj1, $obj2);
    }

    public function testNewInstanceConstructor() 
    {
        $obj1 = $this->target->getNewInstance('test', array('a', 'b', 'c'));
        $this->assertSame('a', $obj1->field1);
        $this->assertSame('b', $obj1->field2);
        $this->assertSame('c', $obj1->field3);
    }

    public function testNewInstanceConstructorArgumentCast() 
    {
        $obj1 = $this->target->getNewInstance('test', 'a', 'b', 'c');
        $this->assertSame('a', $obj1->field1);
        $this->assertSame('b', $obj1->field2);
        $this->assertSame('c', $obj1->field3);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyKeySetFlyWeight () 
    {
        $this->target->setFlyWeight(null, function(){});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyKeySetNew () 
    {
        $this->target->setNew(null, function(){});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidKeySetFlyWeight () 
    {
        $this->target->setFlyWeight(new \stdClass(), function(){});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidKeySetNew () 
    {
        $this->target->setNew(new \stdClass(), function(){});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDuplicateSetFlyWeight () 
    {
        $this->target->setFlyWeight('test', function(){});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDuplicateSetNew () 
    {
        $this->target->setNew('test', function(){});
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyConstructorGetNew () 
    {
        $this->target->getNewInstance(null);
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyConstructorGetFlyWeight () 
    {
        $this->target->getFlyWieght(null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConstructorGetNew () 
    {
        $this->target->getNewInstance(new \stdClass());
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConstructorGetFlyWeight () 
    {
        $this->target->getFlyWieght(new \stdClass());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotExistsConstructorGetNew () 
    {
        $this->target->getNewInstance('not exists');
    }
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotExistsConstructorGetFlyWeight () 
    {
        $this->target->getFlyWieght('not exists');
    }
}
