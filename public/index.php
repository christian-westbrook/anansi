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
		<?php
			# Load and read system configuration file
			$configFile = fopen("config.json", "r") or die("Unable to find config.json");
			$configJson = fread($configFile, filesize("config.json"));
			fclose($configFile);

			# Extract configuration settings from raw JSON
			$config = json_decode($configJson);

			# Build the header
			echo "<div id=\"header\">";
			echo "<h3><a href=\"$config->domain\">$config->title</a></h3>";
			echo "</div>";

			# Build the blog feed using a BlogEngine object
			$blogEngine = new BlogEngine('./blogs/');
			$blogFeed = $blogEngine->generateBlogFeed();

			# Render each blog in the feed
			foreach($blogFeed as $blog)
				echo $blog;
		?>
	</body>
</html>
