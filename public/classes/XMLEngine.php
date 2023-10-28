<?php
# ------------------------------------------------------------------------------
# System   : Portfolio Web System
# Class    : XMLEngine.php
# Engineer : Christian Westbrook
# Abstract : This class provides an interface of support methods that handle
#            XML parsing and conversions to HTML for the portfolio web system.
# ------------------------------------------------------------------------------
class XMLEngine {

	// ----------------------- Public Interface ---------------------------------
	// ---------------------------------------------------------------------------
	// Method     : extractBlogFromXML()
	// Engineer   : Christian Westbrook
	// Parameters : $fullBlogPath - A string representing a relative path from the 
	//              root web directory to a blog XML file.
	// Output     : $blog - A dictionary mapping detected XML tags to their
	//              respective content.
	// Abstract   : This method extracts a blog from an XML file by storing its
	//              content tags as keys and its content as values in a dictionary.
	//
	//              The method begins by opening a file stream on the given blog
	//              path. The strategy for extracting blog content is to make a
	//              single pass through the file stream looking for XML tags and
	//              their associated content and storing them in a dictionary for
	//              further processing. The method ends by closing the file stream 
	#               and returning the dictionary of detected tags and content.
	// ---------------------------------------------------------------------------
	public function extractBlogFromXML($fullBlogPath) {
		// Open a file stream
		$handle = fopen($fullBlogPath, 'r');

		// Control variables
		$state = array();
		$state['inBlog'] = false;
		$state['opening'] = false;
		$state['reading'] = false;
		$state['closing'] = false;
		$state['tagComparisonIndex'] = NULL;

		// Storage variables;
		$blog = array();
		$tag = '';
		$content = '';
		$match = '';

		// Iterate through characters in the file stream
		while(!feof($handle)) {
			// Get the next character
			$character = fgetc($handle);

			// If we aren't in a blog yet
			if(!$state['inBlog']) {
				$match .= $character;
				if(preg_match('/<blog>/i', $match)) {
					$match = '';
					$state['inBlog'] = true;
				}
			}
			// If we are looking for an opening content tag
			else if($state['inBlog'] && !$state['opening'] && !$state['reading'] && !$state['closing']) {
				if($character == '<') {
					$state['opening'] = true;
				}
				else {
					continue;
				}
			}

			// If we are currently opening a new content tag
			else if($state['inBlog'] && $state['opening'] && !$state['reading'] && !$state['closing']) {
				if($character == '>') {
					$state['opening'] = false;

					if($tag == '</blog>') {
						$tag = '';
						$state['inBlog'] = false;
					}
					else {
						$state['reading'] = true;
					}
				}
				else {
					$tag .= $character;
				}
			}


			// If we are currently reading content
			else if($state['inBlog'] && !$state['opening'] && $state['reading'] && !$state['closing']) {
				if($character == '<') {
					$state['reading'] = false;
					$state['closing'] = true;
					$state['tagComparisonIndex'] = 0;
				}
				else {
					$content .= $character;
				}
			}

			// If we are currently closing a tag
			else if($state['inBlog'] && !$state['opening'] && !$state['reading'] && $state['closing']) {
				if($character = '>') {

					// Do stuff with the tag and content
					$blog[$tag] = $content;

					$tag = '';
					$content = '';
					$state['tagComparisonIndex'] = NULL;
					$state['closing'] = false;
				}
				else if($character != substr($tag, $state['tagComparisonIndex'], 1)) {
					echo "Error: Bad closing tag!";
					break;
				}
				else {
					$state['tagComparisonIndex']++;
				}
			}

			// If some illegal state is reached
			else {
				echo "Error: Illegal state reached during blog extraction!";
				echo $state['opening'];
				echo $state['reading'];
				echo $state['closing'];
				break;
			}

		}

		fclose($handle);

		// Generate a sortable datetime and attach it to the blog
		$blog['sortableDateTime'] = $this->generateSortableDateTime($blog['date'], $blog['time']);

		return $blog;
	}
	// ---------------------------------------------------------------------------

	// ---------------------------------------------------------------------------
	// ---------------------------------------------------------------------------
	public function getBlogHTML($blog) {
		return $this->convertXMLBlogDataToHTML($blog, 'content');
	}
	// ---------------------------------------------------------------------------

	// ---------------------------------------------------------------------------
	// ---------------------------------------------------------------------------
	public function getBlogExcerptHTML($blog) {
		return $this->convertXMLBlogDataToHTML($blog, 'excerpt');
	}
	// ---------------------------------------------------------------------------
	// ---------------------------------------------------------------------------

	// ----------------------- Private Interface ---------------------------------
	// ---------------------------------------------------------------------------
	// Method     : convertXMLBlogDataToHTML()
	// Engineer   : Christian Westbrook
	// Parameters : $blog - A dictionary holding an individual blog's XML tags
	//              mapped to their respective content.
	// Output     : $transformation - A string of HTML content representing the
	//              conversion of the input blog dictionary to an HTML blog post.
	// Abstract   : This function takes in a dictionary mapping blog XML tags to
	//              their respective content and then plugs that content into a
	//              predefined HTML template representing a blog post. This HTML
	//              content is then returned as a string.
	// ---------------------------------------------------------------------------
	private function convertXMLBlogDataToHTML($blog, $content_key) {

		// Control variables
		$state = array();
		$state['inUnorderedList'] = false;

		# Append all desired HTML content to this string
		$transformation = '';

		# Header
		$transformation .= '<div class="blog">';
		$transformation .= '<h1 class="title">' . $blog['title'] . '</h1>';
		$transformation .= '<div class="blog-metadata">';
		$transformation .= '<p class="author">' . $blog['author'] . '</p>';

		$timestamp = strtotime($blog['date'] . ' ' . $blog['time']);
		$formattedDateTime = date('M d, Y g:ia', $timestamp);
		$transformation .= '<p class="date">' . $formattedDateTime . '</p>';
		$transformation .= '</div>';
		$transformation .= '<p class="abstract"><i>' . $blog['abstract'] . '</i></p>';
		$transformation .= '<img class="thumbnail" src="' . $blog['thumbnail'] . '" />';

		# Body
		$transformation .= '<div class="content">';
		$content = $blog[$content_key];

		$lines = explode("\n", $content);

		# Markdown parser
		# For each line of blog content
		foreach($lines as $line) {
			# Trim leading whitespace
			$line = ltrim($line);

			# If the trimmed line is now empty, skip the line
			if($line == '') {
				continue;
			}

			# ------------------------------------------------------------------
			# PROCESS IMAGES
			# ------------------------------------------------------------------
			if(preg_match('/\!\[[\w\s-]+\]\([\w\.\/-]+\)/i', $line, $matches)) {
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

			# ------------------------------------------------------------------
			# PROCESS HYPERLINKS
			# ------------------------------------------------------------------
			if(preg_match_all('/\[[\w\s\.-]+\]\([\w\.\:\/\_-]+\)/i', $line, $matches)) {
				foreach($matches as $match) {
					foreach($match as $original) {
						$reduced = $original;
						$reduced = substr($reduced, 1);
						$reduced = ltrim($reduced, '[');
						$reduced = rtrim($reduced, ')');
						$components = explode('](', $reduced);
						$text = $components[0];
						$href = $components[1];

						$pattern = '/' . str_replace(['(', ')', '[', ']', '/', '!', '.'], ['\(', '\)', '\[', '\]', '\/', '\!', '\.'], $original) . '/i';
						$replacement = '<a class="embeddedLink" href="' . $href . '">' . $text . '<a/>';
						$line = preg_replace($pattern, $replacement, $line);
					}
				}
			}

			# ------------------------------------------------------------------
			# PROCESS BOLDING AND ITALICS
			# ------------------------------------------------------------------
			if(preg_match('/\*\*\*[\w\s\!\?\.,]+\*\*\*/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '*');
					$pattern = '/' . str_replace(['*', '!', '?'], ['\*', '\!', '\?'], $match) . '/i';
					$replacement = '<b><i>' . $target . '</i></b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/___[\w\s\!\?\.,]+___/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '_');
					$pattern = '/' . str_replace(['*', '!', '?'], ['\*', '\!', '\?'], $match) . '/i';
					$replacement = '<b><i>' . $target . '</i></b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/\*\*[\w\s\!\?\.,]+\*\*/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '*');
					$pattern = '/' . str_replace(['*', '!', '?'], ['\*', '\!', '\?'], $match) . '/i';
					$replacement = '<b>' . $target . '</b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/__[\w\s\!\?\.,]+__/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '_');
					$pattern = '/' . str_replace(['*', '!', '?'], ['\*', '\!', '\?'], $match) . '/i';
					$replacement = '<b>' . $target . '</b>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/\*[\w\s\!\?\.,]+\*/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '*');
					$pattern = '/' . str_replace(['*', '!', '?'], ['\*', '\!', '\?'], $match) . '/i';
					$replacement = '<i>' . $target . '</i>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}
			if(preg_match('/_[\w\s\!\?\.,]+_/i', $line, $matches)) {
				foreach($matches as $match) {
					$target = trim($match, '_');
					$pattern = '/' . str_replace(['*', '!', '?'], ['\*', '\!', '\?'], $match) . '/i';
					$replacement = '<i>' . $target . '</i>';
					$line = preg_replace($pattern, $replacement, $line);
				}
			}

			# ------------------------------------------------------------------
			# PROCESS HEADINGS
			# ------------------------------------------------------------------
			if(preg_match('/######.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<br/><h6 class="embeddedHeading">' . $target . '</h6>';
			}
			if(preg_match('/#####.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<br/><h5 class="embeddedHeading">' . $target . '</h5>';
			}
			if(preg_match('/####.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<br/><h4 class="embeddedHeading">' . $target . '</h4>';
			}
			if(preg_match('/###.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<br/><h3 class="embeddedHeading">' . $target . '</h3>';
			}
			if(preg_match('/##.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<br/><h2 class="embeddedHeading">' . $target . '</h2>';
			}
			if(preg_match('/#.+/i', $line)) {
				$target = ltrim($line, '#');
				$line = '<br/><h1 class="embeddedHeading">' . $target . '</h1>';
			}

			# ------------------------------------------------------------------
			# PROCESS UNORDERED LISTS
			# ------------------------------------------------------------------
			if(preg_match('/\*.+/i', $line)) {

				# Remove the leading asterisk
				$target = ltrim($line, '*');

				# If this is the start of a new unordered list, add a line starting the list
				if($state['inUnorderedList'] == false) {
					$transformation .= '<ul>';
					$state['inUnorderedList'] = true;
				}
				
				# Add the current line as a list item
				$line = '<li>' . $target . '</li>';
			}
			else {
				# Check to see if an unordered list just ended
				if($state['inUnorderedList'] == true) {
					$transformation .= '</ul>';
					$state['inUnorderedList'] = false;
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
	// ---------------------------------------------------------------------------

	// ---------------------------------------------------------------------------
	// Method     : generateSortableDateTime()
	// Engineer   : Christian Westbrook
	// Parameters : $date - A string representing the date at which a blog post
	//              was, or will be, posted. Format: MM/DD/YYYY
	//
	//              $time - A string representing the time of day at which a blog
	//              post was, or will be, posted. Format: HH:MM
	//
	// Output     : $sortableDateTime - A string representing the date and time
	//              of day at which a blog post was, or will be, posted in a format
	//              that is more easily sorted. Format: YYYYMMDDHHMM
	//
	// Abstract   : This method converts the date and time extracted from a blog
	//              XML file into a single string variable formatted for use as
	//              a key to sort blog posts against.
	// ---------------------------------------------------------------------------
	private function generateSortableDateTime($date, $time) {

		// Split the date and time strings into elements
		[$month, $day, $year] = explode("/", $date);
		[$hour, $minute] = explode(":", $time);

		// Recombine the elements from highest to lowest priority when sorting
		$sortableDateTime = $year . $month . $day . $hour . $minute;

		return $sortableDateTime;
	}
	// --------------------------------------------------------------------------
	// ---------------------------------------------------------------------------
}
?>