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
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);

			session_start();

			# Get count of views from views.txt
			$views_file = fopen("views.txt", "r") or die("Unable to open session file!");
			$views_line = fgets($views_file);
			$views = intval($views_line);
			fclose($views_file);

			# Increment views by one
			$views = $views + 1;

			# Write new views count to disk
			$views_file = fopen("views.txt", "w+");
			fwrite($views_file, strval($views));
			fclose($views_file);

			# Load and read system configuration file
			$configFile = fopen("config.json", "r") or die("Unable to find config.json");
			$configJson = fread($configFile, filesize("config.json"));
			fclose($configFile);

			# Extract configuration settings from raw JSON
			$config = json_decode($configJson);

			# Build the header
			echo "<div id=\"header\">";
			echo "<h3><a href=\"$config->domain\">$config->title</a></h3>";
			echo "<p id=\"views\">{$views} views</p>";
			echo "</div>";
			echo "<hr id=\"header-divider\">";

			# Build the blog feed using a BlogEngine object
			$blogEngine = new BlogEngine('./blogs/');
			$blogFeed = $blogEngine->generateBlogFeed();

			# Render each blog in the feed
			foreach($blogFeed as $blog)
				echo $blog;
		?>
	</body>
</html>
