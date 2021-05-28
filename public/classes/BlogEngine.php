<?php
# ------------------------------------------------------------------------------
# Class    : BlogEngine.php
# Engineer : Christian Westbrook
# Abstract : This class handles blog processing for the portfolio web system.
# ------------------------------------------------------------------------------
class BlogEngine {

	# Path to a directory where we should look for blog posts
	private $blogSearchPath;

	# --------------------------------------------------------------------------
	# Constructor
	# Engineer   : Christian Westbrook
	# Parameters : $blogSearchPath - A path to a directory where we should look
	#              for blog posts.
	# Abstract   : This constructor initializes a BlogEngine object by setting
	#              the search path where we should look for blog posts.
	# --------------------------------------------------------------------------
	function __construct($blogSearchPath) {
		$this->blogSearchPath = $blogSearchPath;
	}
}
?>