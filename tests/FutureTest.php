<?php
namespace MichalJarnot\Tests;

use MichalJarnot\Future;
use PHPUnit\Framework\TestCase;

class FutureTest extends TestCase
{
    public function testIgnoreAddMethod()
    {
        $number = $this->add(5)->getCounterValue();
        $this->assertEquals(0, $number);
    }

    public function testExecuteAddMethod()
    {
        $number = $this->add(7)->subtract(1)->getCounterValue();
        $this->assertEquals(6, $number);
    }

    public function testExecuteAddMethodMultipleLines()
    {
        $number = $this->add(7)
            ->subtract(1)
            ->getCounterValue();
        $this->assertEquals(6, $number);
    }

    public function testIgnoreAddMethodMultipleLines()
    {
        $number = $this->add(2)
            ->add(2)
            ->add(2)
            ->add(2)
            ->getCounterValue();
        $this->assertEquals(0, $number);
    }

    public function testIgnoreAddMethodWithSubtractAfterSemicolon()
    {
        $number = $this
            ->add(5)
            ->getCounterValue(); $this->subtract(50);
        $this->assertEquals(0, $number);
    }

    /**
     * TODO: Create separate mock object.
     *
     * Mock counter variable.
     *
     * @var int
     */
    private $counter = 0;

    /**
     * TODO: Create separate mock object.
     *
     * Mock add method.
     *
     * @param $number
     * @return $this
     */
    private function add($number)
    {
        $future = Future::predictFuture(debug_backtrace());

        // Adds to counter only if there is a subtract method somewhere in the future
        if (in_array("subtract", $future)) {
            $this->counter += $number;
        }

        return $this;
    }

    /**
     * TODO: Create separate mock object.
     *
     * Mock subtract method.
     *
     * @param $number
     * @return $this
     */
    private function subtract($number)
    {
        $this->counter -= $number;
        return $this;
    }

    /**
     * TODO: Create separate mock object.
     *
     * Mock result method.
     *
     * @return int
     */
    private function getCounterValue()
    {
        return $this->counter;
    }
}