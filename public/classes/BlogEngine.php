<?php
# ------------------------------------------------------------------------------
# System   : Portfolio Web System
# Class    : BlogEngine.php
# Engineer : Christian Westbrook
# Abstract : This class handles blog processing for the portfolio web system.
#            
#            The primary output of this class is an array of strings representing
#            a blog feed holding the HTML for individual blog posts. A BlogEngine 
#            object is initialized with a path to a location on disk where blog 
#            posts  are stored. A feed can then be generated and retrieved at
#            any time by using the generateBlogFeed() function.
# ------------------------------------------------------------------------------

// Imports
include './classes/XMLEngine.php';

class BlogEngine {

	private $blogSearchPath; # Path to a directory storing blog files
	private $xmlEngine; # Object used to convert blog files from XML to HTML

	# --------------------------------------------------------------------------
	# Constructor
	# Engineer   : Christian Westbrook
	# Parameters : $blogSearchPath - A path to a directory storing blog files.
	# Abstract   : This constructor is responsible for intiailizing a new
	#              XMLEngine object along with accepting and setting the blog
	#              search path.
	# --------------------------------------------------------------------------
	function __construct($blogSearchPath) {
		$this->blogSearchPath = $blogSearchPath;
		$this->xmlEngine = new XMLEngine();
	}
	# --------------------------------------------------------------------------

	# --------------------------------------------------------------------------
	# Method     : generateBlogFeed()
	# Engineer   : Christian Westbrook
	# Abstract   : This function generates an array of strings representing
    #              a blog feed holding the HTML for individual blog posts.
	# --------------------------------------------------------------------------
	public function generateBlogFeed() {
		// Data structures for storing blog posts
		$blogsXML  = array(); # Stores input XML blogs
		$blogsHTML = array(); # Stores output HTML blogs

		// For every blog file in the blog search path
		foreach(scandir($this->blogSearchPath) as $blogFileName) {
			// Skip these paths
			if($blogFileName == '.' || $blogFileName == '..')
				continue;
			if($blogFileName == 'demo.xml')
				continue;

			// Extract XML blog content from the XML file at the given path
			$blogXML = $this->xmlEngine->extractBlogFromXML($this->blogSearchPath . $blogFileName);

			// Add the newly extracted XML blog onto the stack of XML blogs
			array_push($blogsXML, $blogXML);
		}

		// Sort XML blogs on the date field
		array_multisort(array_column($blogsXML, "sortableDateTime"), SORT_DESC, $blogsXML);

		// Convert blogs from XML to HTML and add them to the HTML blog stack
		foreach($blogsXML as $blogXML) {
			$blogHTML = $this->xmlEngine->getBlogHTML($blogXML);
			array_push($blogsHTML, $blogHTML);
		}

		// Return the feed of HTML blogs
		return $blogsHTML;
	}
}
?>