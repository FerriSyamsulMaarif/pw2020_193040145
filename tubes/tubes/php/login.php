<?php 
session_start();
require 'functions.php';

if(isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit;
}

if (isset($_COOKIE['username']) && isset($_COOKIE['hash'])) {
  $username = $_COOKIE['username'];
  $hash = $_COOKIE['hash'];

  $result = mysqli_query(koneksi(), "SELECT * FROM user WHERE username = '$username'");
  $row = mysqli_fetch_assoc($result);

  if ($hash === hash('sha256', $row['id'], false)) {
    $_SESSION['username'] = $row['password'];
    header("Location: admin.php");
    exit;
  }
}

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cek_user = mysqli_query(koneksi(), "SELECT * FROM user WHERE username = '$username'");
    if(mysqli_num_rows($cek_user) > 0) {
        $row = mysqli_fetch_assoc($cek_user);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['hash'] = hash('sha256', $row['id'], false);
        

        if (isset($_POST['remember'])) {
        setcookie('username', $row['username'], time() + 60 + 60 + 24);
        $hash = hash('sha256', $row['id']);
        setcookie('hash', $hash, time() + 60 + 60 + 24);
        }
      }

        if (hash('sha256', $row['id']) == $_SESSION['hash']) {
            header("Location: admin.php");
            die;
        }
        header("Location: ../index.php");
        die;
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <!--Import materialize.css-->
      <link type="text/css" rel="stylesheet" href="../../css/materialize.min.css"  media="screen,projection"/>


      <!-- My CSS -->
      <link rel="stylesheet" href="../../css/style.css">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,0">
    <title>Document</title>
</head><br><br><br>
<body class="grey">

  <div class="rowright center">
    <div class="col s12 m6">
      <div class="card blue-grey darken-1">
        <div class="card-content white-text">
          <span class="card-title">Selamat datang, silahkan masukan Username dan Password anda.</span>
          <p><?php if(isset($error)) : ?>
        <p style="color: red; font-style: italic;">Username atau Password salah</p>
    <?php endif ?>
    <form action="" method="post"> 
       <table>
           <tr>
               <td><label for="username"><b>Username</b></label></td>
               <td>:</td>
               <td><input type="text" name="username"></td>
           </tr>
           <tr>
               <td><label for="password"><b>Password</b></label></td>
               <td>:</td>
               <td><input type="password" name="password"></td>
           </tr>
       </table><br>
       <input type="checkbox" name="remember">
       <label for="rember">Remember Me</label><br>
       <button type="submit" name="submit">Login</button>
   </form>
   <p>Belum punya akun? Registrasi <a href="registrasi.php">disini</a></p><br>
   <button class="tombol-kembali"><a href="../../index1.php">Kembali</a></button>
        </div>
      </div>
    </div>
  </div>

     
</body>

</html>