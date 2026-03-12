<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Iniciar Sesión</title>
  <!-- Bootstrap -->
  <link href="css/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="css/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="css/animate.css/animate.min.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="css/custom.min.css" rel="stylesheet">
</head>

<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
      <div class="form-group">
        <div class="profile_pic">
          <img src="images/logo.png" class="profile_img" style=" margin-bottom:100px; width: 150px; height: 50px; border: 100px">
        </div>
      </div>

      <div class="animate form login_form">
        <section class="login_content">
          <form method="POST" action="../unisersoft/login.php">
            <div class="form-group">
              <label class="xx-larger mb-6" for="inputEmailAddress">Usuario</label>
              <input class="form-control py-4" id="inputEmailAddress" type="text" name="usuario" id="usuario" placeholder="Ingresa tu usuario" />
            </div>
            <div class="form-group">
              <label class="xx-larger mb-6" for="inputPassword">Contraseña</label>
              <input class="form-control py-4" id="inputPassword" type="password" name="password" id="password" placeholder="Ingresa tu contraseña" />
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
              <button class="btn btn-primary" type="submit" name="log"> <i class="fas fa-sign-in-alt"></i> Ingresar</button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
</body>

</html>