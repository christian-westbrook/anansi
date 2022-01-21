<?php
# ------------------------------------------------------------------------------
# System   : Portfolio Web System
# Class    : BlogEngine.php
# Engineer : Christian Westbrook
# Abstract : This class handles blog processing for the portfolio web system.
# ------------------------------------------------------------------------------

// Imports
include './classes/XMLEngine.php';

class BlogEngine {

	# Path to a directory where we should look for blog posts
	private $blogSearchPath;
	private $xmlEngine;

	# --------------------------------------------------------------------------
	# Constructor
	# Engineer   : Christian Westbrook
	# Parameters : $blogSearchPath - A path to a directory where we should look
	#              for blog posts.
	# Abstract   : This constructor initializes a BlogEngine object by setting
	#              the search path to where we should look for blog posts.
	# --------------------------------------------------------------------------
	function __construct($blogSearchPath) {
		$this->blogSearchPath = $blogSearchPath;
		$this->xmlEngine = new XMLEngine();
	}

	# --------------------------------------------------------------------------
	# Method     : generateBlogFeed()
	# Engineer   : Christian Westbrook
	# Abstract   : This function generates a stream of HTML content representing
	#              a blog post feed from all blog posts found in the blog search
	#              path.
	# --------------------------------------------------------------------------
	public function generateBlogFeed() {
		// Data structures for storing blog posts
		$blogsXML  = array();
		$blogsHTML = array();

		// For every blog file in the blog search path
		foreach(scandir($this->blogSearchPath) as $partialBlogPath) {
			// Skip these paths
			if($partialBlogPath == '.' || $partialBlogPath == '..')
				continue;
			if($partialBlogPath == 'demo.xml')
				continue;

			// Extract XML blog content from the XML file at the given path
			$blogXML = $this->xmlEngine->extractBlogFromXML($this->blogSearchPath . $partialBlogPath);

			// Add the newly extracted XML blog onto the stack of XML blogs
			array_push($blogsXML, $blogXML);
		}

		// Sort XML blogs on the date field
		array_multisort(array_column($blogsXML, "date"), SORT_DESC, $blogsXML);

		// Convert blogs from XML to HTML and add to the HTML blog stack
		foreach($blogsXML as $blogXML) {
			$blogHTML = $this->xmlEngine->convertXMLBlogToHTML($blogXML);
			array_push($blogsHTML, $blogHTML);
		}

		// Return feed of HTML blogs
		return $blogsHTML;
	}
}
?>