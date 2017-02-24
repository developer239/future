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
     * Returns array of substrings from a string.
     *
     * @param $string
     * @param $start
     * @return array
     */
    private static function getMethodNamesBetween($string, $start)
    {
        $expression = explode(";", $string)[0];

        $first_pos = strpos($expression, $start);
        $last_pos = strlen($expression);
        $capture_len = $last_pos - $first_pos;

        return substr($expression, $first_pos, $capture_len);
    }

    /**
     * Returns array of methods that are chained on method that called this.
     *
     * @param array $debug_backtrace
     * @return array
     */
    public static function predictFuture($debug_backtrace)
    {
        // Get debug backtrace of a calling method
        $debug_backtrace = $debug_backtrace[0];

        // Get target file
        $file = file($debug_backtrace["file"]);

        // Build expression string
        $search_string = "";
        $line_index = 1;
        $semicolon_not_found = true;

        while($semicolon_not_found) {
            $line_content = $file[($debug_backtrace["line"] - $line_index)];
            $search_string .= trim($line_content);

            if(strpos($line_content, ";") !== false) {
                $semicolon_not_found = false;
            }

            $line_index -= 1;
        }

        // Start on target line from a calling method with its parameters
        $start = $debug_backtrace["function"] . "(" . implode(", ", $debug_backtrace["args"]) . ")";

        // Get the methods chain
        $target_line_substring = self::getMethodNamesBetween($search_string, $start);

        // Get all method names from the string
        $future_methods = explode("->", trim(preg_replace('/\s*\([^)]*\)/', '', $target_line_substring)));

        return $future_methods;
    }
}
