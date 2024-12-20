
<!DOCTYPE html>

 <html class="no-js"> 
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"> 


	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/style.css">


	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>

	</head>
	<body>

		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					

					<!-- Start Sign In Form -->
					<form action="config/login.php"  id="form_login" method="POST" class="fh5co-form animate-box" data-animate-effect="fadeIn">
						<h2>Sign In</h2>
						<div class="form-group">
							<div class="alert alert-success" role="alert" style="display: none;" >Success</div>
						</div>
						<div class="form-group">
							<label for="username" class="sr-only">Username</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="Username" autocomplete="off" required="">
						</div>
						<div class="form-group">
							<label for="password" class="sr-only">Password</label>
							<input type="password" name="password" class="form-control" id="password" placeholder="Password" autocomplete="off" required="">
						</div>
						<!-- <div class="form-group">
							<label for="remember"><input type="checkbox" id="remember"> Remember Me</label>
						</div> -->
						<div class="form-group">
							 <p>Not remember a password ? <!-- <a href="sign-up.html">Sign Up</a> |--> <a href="forgot.html">Forgot Password?</a></p> 
						</div>
						<div class="form-group">
							<input type="submit" value="Sign In" class="btn btn-primary" id="loginButton">
						</div>
					</form>
					<!-- END Sign In Form -->

					<?php
    // Verificar si existe una cookie de remember me
    if(isset($_COOKIE['remember_me_token'])) {
        echo '<script>
            document.getElementById("usuario").value = "' . htmlspecialchars($_COOKIE['saved_usuario']) . '";
            document.getElementById("rememberMe").checked = true;
        </script>';
    }
    ?>

				</div>
			</div>
			<div class="row" style="padding-top: 60px; clear: both;">
				<div class="col-md-12 text-center"><p><small>&copy; Copyright © Noel Dobarganes all rights reserved.</small></p></div>
			</div>
		</div>
	
	<!-- jQuery -->
	<script src="js/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Placeholder -->
	<script src="js/jquery.placeholder.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<!-- Main JS -->
	<script src="js/main.js"></script>

	<script>
    $(document).ready(function() {
        // Evento para el botón de inicio de sesión
        $("#loginButton").click(function(e) {
            e.preventDefault(); // Previene el envío del formulario
            const username = $("#username").val();
            const password = $("#password").val();

            // Validación básica de campos vacíos
            if (username === "" || password === "") {
                showAlert("Both fields are required.", "alert-danger");
                return;
            }

            // Llamada AJAX para el inicio de sesión
            $.ajax({
                type: "POST",
                url: "config/login.php",
                data: { username: username, password: password },
                success: function(response) {
                    const alertBox = $("#form_login .alert"); // Selecciona el alert dentro del formulario
                    
                    if (response.trim() === "success") {
                        const successMessage = "¡Inicio de sesión exitoso! Redirigiendo...";
                        alertBox.removeClass("alert-danger").addClass("alert-success");
                        alertBox.text(successMessage);
                        alertBox.css("display", "block");
                        setTimeout(function() {
                            window.location.href = "upload.php"; // Redirige después de 3 segundos
                        }, 1000);
                    } else {
                        // Mostrar mensaje de error de forma permanente
                        alertBox.removeClass("alert-success").addClass("alert-danger");
                    alertBox.text(response);
                    alertBox.css("display", "block");
                    }
                },
                error: function() {
                    const alertBox = $("#form_login .alert"); // Selecciona el alert dentro del formulario
                alertBox.removeClass("alert-success").addClass("alert-danger");
                alertBox.text("Hubo un error al Intentar loguearse. Inténtalo de nuevo.");
                alertBox.css("display", "block");
                }
            });
        });

        // Función para mostrar mensajes en la alerta
        function showAlert(message, alertClass, autoHide = false) {
            const alertBox = $("#login-alert");
            alertBox.removeClass("alert-success alert-danger").addClass(alertClass);
            alertBox.text(message);
            alertBox.show();

            if (autoHide) {
                setTimeout(function() { alertBox.hide(); }, 3000);
            }
        }
    });
</script>

	</body>
</html>

