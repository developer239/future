<?php

namespace MichalJarnot;

/**
 * This class was created as a joke with my colleagues at work. It is supposed to
 * return names of all methods that are chained on some particular method.
 *
 * Lets say that you have something like $count = $totalCount->add(5)->subtract(3)->divide(2)->result()
 *
 * Now if you want to know what methods will follow after method add() is executed
 * simply call \MichalJarnot\Future::predictFuture(). You will get an array with the information
 * you need.
 *
 * Class Future
 * @package MichalJarnot
 */
class Future
{
    /**
     * Returns expression string.
     *
     * When we have code like `$start->add(4)->subtract(5)->getResult()`
     * and the add method calls `Future::predictFuture` method
     * then this method should return `->subtract(5)->getResult()`
     *
     * @param $string
     * @param $start
     * @return string
     */
    private static function getMethodNamesBetween($string, $start)
    {
        $expression = explode(";", $string)[0];

        $firstPos = strpos($expression, $start);
        $lastPos = strlen($expression);
        $captureLen = $lastPos - $firstPos;

        return substr($expression, $firstPos, $captureLen);
    }

    /**
     * Returns array of methods that are chained on method that called this.
     *
     * When we have code like `$start->add(4)->subtract(5)->getResult()`
     * and the add method calls `Future::predictFuture` method
     * then this method should return ['subtract', 'getResult']
     *
     * @param array $debugBacktrace
     * @return array
     */
    public static function predictFuture($debugBacktrace)
    {
        // Get debug backtrace of a calling method
        $debugBacktrace = $debugBacktrace[0];

        // Get target file
        $file = file($debugBacktrace["file"]);

        // Build expression string
        $searchString = "";
        $lineIndex = 1;
        $semicolonNotFound = true;

        while($semicolonNotFound) {
            $lineContent = $file[($debugBacktrace["line"] - $lineIndex)];
            $searchString .= trim($lineContent);

            if(strpos($lineContent, ";") !== false) {
                $semicolonNotFound = false;
            }

            $lineIndex -= 1;
        }

        // Start on target line from a calling method with its parameters
        $start = $debugBacktrace["function"] . "(" . implode(", ", $debugBacktrace["args"]) . ")";

        // Get the methods chain
        $targetLineSubstring = self::getMethodNamesBetween($searchString, $start);

        // Get all method names from the string
        $futureMethods = explode("->", trim(preg_replace('/\s*\([^)]*\)/', '', $targetLineSubstring)));

        return $futureMethods;
    }
}
