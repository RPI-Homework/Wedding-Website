<html>
	<head>
		<style type="text/css" media="all"> @import "./css.css";</style>
		<title>Wedding of Samantha & Joseph</title>
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
						include('../pages.php');

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
					if(is_null($_GET['idx']) || !file_exists('../pages/' . strtolower($_GET['idx']) . '.txt'))
					{
						$_GET['idx'] = 'home';
					}

					if(strlen($topbar[$_GET['idx']]['title']) > 0)
					{
						echo "<h1>" . $topbar[$_GET['idx']]['title'] . "</h1>";
					}
					require('../pages/' . strtolower($_GET['idx']) . '.txt');
				?>
			</div> <!-- content -->
		</div> <!-- wrapper -->

		<div id="footer">
		</div> <!-- footer -->
	</body>
</html>