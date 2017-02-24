Imagine that you have something like this:

    $start->yourSadMethod()->someRandomStuff()->yourColleaguesCoolMethod()->result();

Do you find yourself writing bad code too often? Are your colleagues angry at you because your code breaks their cool code?

Don't worry. Now you can adapt behaviour of your code according to the code that follows after your methods are executed.

    public function yourSadMethod() {
	    $future = \MichalJarnot\Future::predictFuture(debug_backtrace());
	    
	    // If there is your colleague's cool method about to be executed you can just omit your code
	    if (in_array("yourColleaguesCoolMethod", $future)) {
	        return $this;
        } else {
	        // do the ugly stuff here
        }
    }

Happy debugging!