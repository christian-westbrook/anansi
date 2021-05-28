<!DOCTYPE html>
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
			$blogEngine = new BlogEngine('./data/');
			$blogFeed = $blogEngine->generateBlogFeed();
			echo $blogFeed;
		?>
	</body>
</html>
