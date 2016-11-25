<html>
	<head>

		<!-- link to external style sheet -->
		<style type="text/css" media="all"> @import "./css.css";</style>
			
		<title>Wedding of Samantha & Joseph</title>

	</head>
	<body>
		<div id="wrapper">
			<div id="header">
			</div> <!-- header -->
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

		<div id="content_outside">
			<div id="content_wrapper">
				<div id="content_inside">
					<div id="inner_padding">
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
						<div class="spacer"></div>
					</div> <!-- inner_padding -->
				</div> <!-- content_inside-->
			</div> <!-- content_wrapper-->
		</div> <!-- content_outside-->
	</body>
</html>