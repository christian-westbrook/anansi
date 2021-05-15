<?php
	include './classes/Blog.php';

	// ---------------------------------------------------------------------------
	// Function   : extractBlogFromXML()
	// Engineer   : Christian Westbrook
	// Parameters : $path - A string representing a relative path from the root
	//              directory to a blog XML file.
	// Abstract   : This function extracts the data from a blog XML file and
	//              stores it in a Blog object.
	// ---------------------------------------------------------------------------
	function extractBlogFromXML($path) {

		// Open a file stream
		$handle = fopen($path, 'r');

		// Control variables
		$post = false;
		$opening = false;
		$reading = false;
		$closing = false;
		$tagComparisonIndex = NULL;

		// Storage variables;
		$tag = '';
		$content = '';
		$match = '';

		// Iterate through characters in the file stream
		while(!feof($handle)) {
			$character = fgetc($handle);

			// If we are looking for an opening tag
			if($post && !$opening && !$reading && !$closing) {
				if($character == '<') {
					$opening = true;
				}
				else {
					continue;
				}
			}

			// If we are currently opening a new tag
			else if($post && $opening && !$reading && !$closing) {
				if($character == '>') {
					$opening = false;

					if($tag == '</post>') {
						$tag = '';
						$post = false;
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
			else if($post && !$opening && $reading && !$closing) {
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
			else if($post && !$opening && !$reading && $closing) {
				if($character = '>') {

					// Do stuff with the tag and content
					print($tag . '<br />');
					print($content . '<br />');
					print('<br />');

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

			else if(!$post) {
				$match .= $character;
				if(preg_match('/<post>/i', $match)) {
					$match = '';
					$post = true;
				}
			}

			// If some illegal state is reached
			else {
				echo "Error: Illegal state reached during blog post extraction!";
				echo $opening;
				echo $reading;
				echo $closing;
				break;
			}

		}
	}
?>