<!DOCTYPE html>
<html>
	<head>
			<title>christianwestbrook.dev</title>
			<link type="text/css" rel="stylesheet" href="./styles/index.css" />
			<link type="text/css" rel="stylesheet" href="./styles/blog.css" />

			<?php
				include './modules/blog-utilities.php';
			?>
	</head>

	<body>
		<div id="header">
			<h3>christianwestbrook.dev</h3>
		</div>

		<?php
			foreach(scandir('./data/') as $path) {

				if ($path == '.' || $path == '..')
					continue;

				$blog = extractBlogFromXML('./data/' . $path);
				$blog = transformBlog($blog);
				
				echo $blog;
			}
		?>
	</body>
</html>
