<html>
	<head>
		<style type="text/css" media="all"> @import "./css.css";</style>
		<link rel="stylesheet" type="text/css" href="./scripts/jquery.ad-gallery.css">
		<script type="text/javascript" src="./scripts/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="./scripts/jquery.ad-gallery.js"></script>
		<title>Wedding of Samantha & Joseph</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>

	<body>
		<div id="header">
			<div id="banner">
			</div> 
		</div>

		<div id="menu">
			<div id="navcontainer">
				<ul id="navlist">
					<?php
						include('pages.php');

						foreach($topbar as $key => $value)
						{
							echo "<li><a href='./index.php?idx=$key'>" . $value['menu'] . "</a></li>";
						}
					?>
				</ul>
			</div> <!-- navcontainer -->
		</div> <!-- menu -->

		<div id="wrapper">
			<div id="content">
				<?php
					if(is_null($_GET['idx']) || !file_exists('pages/' . strtolower($_GET['idx']) . '.txt'))
					{
						$_GET['idx'] = 'home';
					}
					if(strlen($topbar[$_GET['idx']]['title']) > 0)
					{
						echo "<h1>" . $topbar[$_GET['idx']]['title'] . "</h1>";
					}
					require('pages/' . strtolower($_GET['idx']) . '.txt');
				?>
				<div class="spacer"></div>
			</div> <!-- content -->
		</div> <!-- wrapper -->

		<div id="footer">
		</div> <!-- footer -->
	</body>
</html>