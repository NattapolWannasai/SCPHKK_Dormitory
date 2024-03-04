<!doctype html>
<?php require_once "admin/connect.php" ?>
<?php session_start(); ?>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<section class="ftco-section">
		<div class="container">

			<body style="background-color:powderblue;">
				<div class="row justify-content-center">
					<div class="col-md-7 col-lg-5">
						<div class="wrap">
							<div class="img" style="background-image: url(assets/images/pic-slide2.png);"></div>
							<div class="login-wrap p-4 p-md-5">
								<div class="d-flex">
									<div class="w-100">
										<h3 class="mb-4">เข้าสู่ระบบ</h3>
									</div>
								</div>
								<form method="POST" action="logincheck.php" class="signin-form">
									<div class="form-group mt-3">
										<input type="text" name="username" class="form-control" required>
										<label class="form-control-placeholder" for="username">รหัสนักศึกษา</label>
									</div>
									<div class="form-group">
										<input id="password-field" name="password" type="password" class="form-control"
											required>
										<label class="form-control-placeholder" for="password">รหัสนักศึกษา</label>
										<span toggle="#password-field"
											class="fa fa-fw fa-eye field-icon toggle-password"></span>
									</div>

									<?php
									if (isset($_SESSION["Error"])) {
										echo "<div class='text-danger'>";
										echo $_SESSION["Error"];
										echo "</div>";
										unset($_SESSION["Error"]);
									}
									?>

									<div class="form-group">
										<button name="login"
											class="form-control btn btn-primary rounded submit px-3">เข้าสู่ระบบ</button>
									</div>
									<!-- <div class="form-group d-md-flex">
										<div class="w-50 text-left">
											<label class="checkbox-wrap checkbox-primary mb-0">จำจด
												<input type="checkbox" checked>
												<span class="checkmark"></span>
											</label>
										</div>
										<div class="w-50 text-md-right">
											<a href="#">ลืมรหัสผ่าน</a>
										</div>
									</div> -->
								</form>
							</div>
						</div>
					</div>
				</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

</body>

</html>