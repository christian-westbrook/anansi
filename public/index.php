<!DOCTYPE html>
<html>
	<head>
			<title>christianwestbrook.dev</title>
			<link type="text/css" rel="stylesheet" href="./styles/index.css" />
			<link type="text/css" rel="stylesheet" href="./styles/blog.css" />
			<?php include './modules/blog-utilities.php'; ?>
	</head>

	<body>
		<div id="header">
			<h3>christianwestbrook.dev</h3>
		</div>

		<?php
			// Array to store extracted blog posts
			$blogs = array();

			// For every blog file in ./data
			foreach(scandir('./data/') as $path) {
				if ($path == '.' || $path == '..')
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

		?>
	</body>
</html>
