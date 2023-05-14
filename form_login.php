<?php 
include('koneksi.php');
if(isset($_POST['tombol']))
{

    session_start();
	session_destroy();

	// ambil data login pengguna
	$email = $_POST['email_user'];
	$password = $_POST['password_user'];

	// lihat apakah pasangan email dan password terdapat pada database dan mengambil idpem
  	$loggedin_user = mysqli_query($koneksi,"SELECT email_user, password_user, id_user FROM pengguna WHERE email_user='$email' AND password_user='$password'");
	$row = mysqli_fetch_array($loggedin_user);
	$iduser = $row['id_user'];

	// apakah pasangan email dan password terdapat dalam database?
	if(mysqli_num_rows($loggedin_user) == 0) {
		// kalau tidak, alihkan ke halaman loginp.php dengan cek_owner=salah
		echo "<p>Email/pasword salah</p>";
	} else {
		// kalau ada, mulai sesi, set iduser, alihkan ke halaman pengguna.php
		session_start();
		$_SESSION["id_user"] = $iduser;
		header("Location: sukseslogin.php");
	}

}
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form method="post" action="" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Alamat email</td>
                <td><input type="text" name="email_user"></textarea></td>
            </tr>
            <tr>
                <td>Kata sandi</td>
                <td><input type="password" name="password_user"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="tombol"/></td>
            </tr>
        </table>
        </form>

    </body>
</html>