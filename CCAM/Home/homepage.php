<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Landing Page | CCAM</title>
  <link rel="stylesheet" type="text/css" href="homestyle1.css">
  <link rel="icon" type="image" href="../Photos/LOGO.png">
</head>
<body>
  <header>
    <div class="logo">
		<a href="homepage.php"> 
			<img src="../Photos/LOGO.png">
		</a>
    </div>
    <nav>
      <a href="../AboutUs/aboutus.php">About Us</a>
      <a href="../Login/login.php">Log in</a>
      <a href="../Signup/signup.php">Sign Up</a>
    </nav>
  </header>

  <section class="main-content">
    <div class="main-logo">
      <img src="../Photos/LOGO.png">
    </div>
    <p class="description">
      a comprehensive software solution designed to streamline financial operations for businesses of all sizes. It automates key accounting tasks such as invoicing, payroll processing, tax calculations, and financial reporting, reducing manual effort and minimizing errors. With features like a general ledger, accounts payable and receivable management, budgeting tools, and real-time financial reporting, an AMS ensures accurate record-keeping and compliance with tax regulations. It also enhances efficiency by providing automated tracking of income and expenses, enabling businesses to make informed financial decisions. Additionally, many systems offer multi-user access and multi-currency support, making them ideal for businesses operating globally. By improving financial transparency and reducing administrative workload, an Accounting Management System helps organizations maintain financial stability and focus on growth.
    </p>

    <div class="blocks">
      <div class="block gold"></div>
      <div class="block green"></div>
      <div class="block blue"></div>
      <div class="block green"></div>
	  <div class="block gold"></div>
    </div>

    <p class="cta">START YOUR FINANCE JOURNEY WITH</p>
    <div class="cta-logo">
      <img src="../Photos/LOGO.png">
    </div>
    <p class="cta-subtext">Dive deeper into your finances and redefine what’s within your grasp</p>
  </section>

  <footer class="footer">
		<img src="Logo.png">
    <p>© 2025 CCAM. All rights reserved.</p>
  </footer>
  <script type= "text/javascript">
			window.addEventListener("scroll", function(){
				var header = document.querySelector("header");
				header.classList.toggle("sticky", window.scrollY > 0);
			});
		</script>
</body>
</html>
