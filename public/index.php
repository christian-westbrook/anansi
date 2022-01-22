<!DOCTYPE html>
<!-- ------------------------------------------------------------------------- -->
<!-- System   : Portfolio Web System                                           -->
<!-- Script   : index.php                                                      -->
<!-- Engineer : Christian Westbrook                                            -->
<!-- Abstract : This script serves as a landing page and entry point to the    -->
<!--            web system.                                                    -->
<!-- ------------------------------------------------------------------------- -->
<html>
	<head>
			<title>christianwestbrook.dev</title>
			<link type="text/css" rel="stylesheet" href="./styles/index.css" />
			<link type="text/css" rel="stylesheet" href="./styles/blog.css" />
			<?php include './classes/BlogEngine.php'; ?>
	</head>

	<body>
		<div id="header">
			<a href="/"><h3>christianwestbrook.dev</h3></a>
		</div>

		<?php
			# Build the blog feed using a BlogEngine object
			$blogEngine = new BlogEngine('./blogs/');
			$blogFeed = $blogEngine->generateBlogFeed();

			# Render each blog in the feed
			foreach($blogFeed as $blog)
				echo $blog;
		?>
	</body>
</html>
