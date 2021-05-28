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

	# --------------------------------------------------------------------------
	# Method     : generateBlogFeed()
	# Engineer   : Christian Westbrook
	# Abstract   : This function generates a stream of HTML content representing
	#              a blog post feed from all blog posts found in the blog search
	#              path.
	# --------------------------------------------------------------------------
	public function generateBlogFeed() {
		// Array for storing extracted blog posts
		$blogs = array();

		// For every blog file in the blog search path
		foreach(scandir($this->blogSearchPath) as $partialBlogPath) {
			if($partialBlogPath == '.' || $partialBlogPath == '..')
				continue;
			if($partialBlogPath == 'demo.xml')
				continue;

			// Extract blog content from the file at the given path
			$blog = $this->extractBlogFromXML($this->blogSearchPath . $partialBlogPath);

			// Add the newly extracted blog onto the stack of blogs
			array_push($blogs, $blog);
		}

		// Sort blogs on the date field
		array_multisort(array_column($blogs, "date"), SORT_DESC, $blogs);

		foreach($blogs as $blog) {
			echo $this->transformBlog($blog);
		}
	}

	// ---------------------------------------------------------------------------
	// Method     : extractBlogFromXML()
	// Engineer   : Christian Westbrook
	// Parameters : $fullBlogPath - A string representing a relative path from the root
	//              directory to a blog XML file.
	// Abstract   : This function extracts the data from a blog XML file and
	//              stores it in an array.
	// ---------------------------------------------------------------------------
	private function extractBlogFromXML($fullBlogPath) {
		// Open a file stream
		$handle = fopen($fullBlogPath, 'r');

		// Control variables
		$inBlog = false;
		$opening = false;
		$reading = false;
		$closing = false;
		$tagComparisonIndex = NULL;

		// Storage variables;
		$blog = array();
		$tag = '';
		$content = '';
		$match = '';

		// Iterate through characters in the file stream
		while(!feof($handle)) {
			$character = fgetc($handle);

			// If we are looking for an opening tag
			if($inBlog && !$opening && !$reading && !$closing) {
				if($character == '<') {
					$opening = true;
				}
				else {
					continue;
				}
			}

			// If we are currently opening a new tag
			else if($inBlog && $opening && !$reading && !$closing) {
				if($character == '>') {
					$opening = false;

					if($tag == '</blog>') {
						$tag = '';
						$inBlog = false;
					}
					else {
						$reading = true;
					}
				}
				else {
					$tag .= $character;
				}
			}


			// If we are currently reading content
			else if($inBlog && !$opening && $reading && !$closing) {
				if($character == '<') {
					$reading = false;
					$closing = true;
					$tagComparisonIndex = 0;
				}
				else {
					$content .= $character;
				}
			}

			// If we are currently closing a tag
			else if($inBlog && !$opening && !$reading && $closing) {
				if($character = '>') {

					// Do stuff with the tag and content
					$blog[$tag] = $content;

					$tag = '';
					$content = '';
					$tagComparisonIndex = NULL;
					$closing = false;
				}
				else if($character != substr($tag, $tagComparisonIndex, 1)) {
					echo "Error: Bad closing tag!";
					break;
				}
				else {
					$tagComparisonIndex++;
				}
			}

			// If we aren't in a inBlog yet
			else if(!$inBlog) {
				$match .= $character;
				if(preg_match('/<blog>/i', $match)) {
					$match = '';
					$inBlog = true;
				}
			}

			// If some illegal state is reached
			else {
				echo "Error: Illegal state reached during blog extraction!";
				echo $opening;
				echo $reading;
				echo $closing;
				break;
			}

		}

		fclose($handle);
		return $blog;
	}

	// ---------------------------------------------------------------------------
	// Function   : transformBlog()
	// Engineer   : Christian Westbrook
	// Parameters : $blog - An array holding an individual blog's data.
	// Abstract   :
	// ---------------------------------------------------------------------------
	private function transformBlog($blog) {
		$transformation = '';

		$transformation .= '<div class="blog">';
		$transformation .= '<h1 class="title">' . $blog['title'] . '</h1>';
		$transformation .= '<div class="blog-metadata">';
		$transformation .= '<p class="author">' . $blog['author'] . '</p>';

		$timestamp = strtotime($blog['date'] . ' ' . $blog['time']);
		$formattedDateTime = date('M d, Y g:ia', $timestamp);
		$transformation .= '<p class="date">' . $formattedDateTime . '</p>';
		$transformation .= '</div>';
		$transformation .= '<img class="thumbnail" src="' . $blog['thumbnail'] . '" />';

		$transformation .= '<div class="content">';
		$content = $blog['content'];

		$lines = explode("\n", $content);

		# Markdown parser
		foreach($lines as $line) {
			# Trim leading whitespace
			$line = ltrim($line);

			# If the trimmed line is now empty, skip the line
			if($line == '') {
				continue;
			}

			# Process bolding and italics
			if(preg_match('/\*\*\*[\w\s,]+\*\*\*/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '*');
					$pattern = '/' . str_replace('*', '\*', $match) . '/i';
					$replacement = '<b><i>' . $target . '</i></b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/___[\w\s,]+___/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '_');
					$pattern = '/' . $match . '/i';
					$replacement = '<b><i>' . $target . '</i></b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/\*\*[\w\s\!,]+\*\*/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '*');
					$pattern = '/' . str_replace('*', '\*', $match) . '/i';
					$replacement = '<b>' . $target . '</b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/__[\w\s,]+__/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '_');
					$pattern = '/' . $match . '/i';
					$replacement = '<b>' . $target . '</b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/\*[\w\s,]+\*/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '*');
					$pattern = '/' . str_replace('*', '\*', $match) . '/i';
					$replacement = '<i>' . $target . '</i>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/_[\w\s,]+_/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '_');
					$pattern = '/' . $match . '/i';
					$replacement = '<i>' . $target . '</i>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}

			# Process headings
			if(preg_match('/######.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<h6 class="embeddedHeading">' . $target . '</h6>';
			}
			if(preg_match('/#####.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<h5 class="embeddedHeading">' . $target . '</h5>';
			}
			if(preg_match('/####.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<h4 class="embeddedHeading">' . $target . '</h4>';
			}
			if(preg_match('/###.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<h3 class="embeddedHeading">' . $target . '</h3>';
			}
			if(preg_match('/##.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<h2 class="embeddedHeading">' . $target . '</h2>';
			}
			if(preg_match('/#.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<h1 class="embeddedHeading">' . $target . '</h1>';
			}

			# Process images
			if(preg_match('/\!\[[\w\s]+\]\([\w\.\/]+\)/i', $line, $matches)) {
				foreach($matches as $match) {
					$reduced = $match;
					$reduced = substr($reduced, 1);
					$reduced = ltrim($reduced, '[');
					$reduced = rtrim($reduced, ')');
					$components = explode('](', $reduced);
					$altText = $components[0];
					$src = $components[1];

					$pattern = '/' . str_replace(['(', ')', '[', ']', '/', '!', '.'], ['\(', '\)', '\[', '\]', '\/', '\!', '\.'], $match) . '/i';
					$replacement = '<img class="embeddedImage" src="' . $src . '" alt="' . $altText . '" /><br/>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}

			# Add a line break if you find two spaces at the end of a line
			if(ctype_space(substr($line, -3))) {
				$line .= "<br/>";
			}

			# Add a line break to all surviving lines
			$transformation .= $line . '<br/>';
		}

		$transformation .= '</div>	';
		$transformation .= '</div>';

		return $transformation;
	}
}
?>