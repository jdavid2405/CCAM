<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Landing Page | CCAM</title>
	<link rel="stylesheet" type="text/css" href="helpcenterstyle.css">
	<link rel="icon" type="image" href="../Photos/LOGO.png">
</head>
<body>
	<header>
		<div class="logo">
			<a href="../Home/homepage.php">
				<img src="../Photos/LOGO.png">
			</a>
		</div>
		
		<h1>Help Center</h1>
		
		<nav class="bar">
			<a href="Request.php">Submit a Request</a>
			<a href="Contact.php">Contact Us</a>
		</nav>
	</header>
	
	<section class="banner"></section>
	
	<div class="blocks">
		<div class="block gold"></div>
		<div class="block green"></div>
		<div class="block blue"></div>
		<div class="block green"></div>
		<div class="block gold"></div>
    </div>
	
	<script type= "text/javascript">
			window.addEventListener("scroll", function(){
				var header = document.querySelector("header");
				header.classList.toggle("sticky", window.scrollY > 0);
			});
	</script>
</body>
</html>