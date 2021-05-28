<?php

include './modules/blog-utilities.php';

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

	# --------------------------------------------------------------------------
	# Function   : generateBlogFeed()
	# Engineer   : Christian Westbrook
	# Abstract   : This function generates a stream of HTML content representing
	#              a blog post feed from all blog posts found in the blog search
	#              path.
	# --------------------------------------------------------------------------
	public function generateBlogFeed() {
		// Array to store extracted blog posts
		$blogs = array();

		// For every blog file in ./data
		foreach(scandir($this->blogSearchPath) as $path) {
			if ($path == '.' || $path == '..' || $path == 'demo.xml')
				continue;

			// Extract blog content from the file at the given path
			// and append the content to the $blogs array
			array_push($blogs, extractBlogFromXML('./data/' . $path));
		}

		// Sort blogs on the date field
		array_multisort(array_column($blogs, "date"), SORT_DESC, $blogs);

		foreach($blogs as $blog) {
			echo transformBlog($blog);
		}
	}
}
?>