<?php 
include('koneksi.php');

session_start();
if($_SESSION['id_user']!=NULL){
		$iduser = $_SESSION["id_user"]; 
	} else {
		header("Location:form_login.php");
}

$query = mysqli_query($koneksi,"SELECT * FROM pengguna WHERE id_user='$iduser';");
$data = mysqli_fetch_array($query);

if(isset($_POST['tombol']))
{
    $nama = $_POST['nama_user'];
    $email = $_POST['email_user'];
    $password = $_POST['password_user'];
    $telp = $_POST['telp_user'];
    mysqli_query($koneksi,"UPDATE pengguna SET nama_user='$nama',email_user='$email',password_user='$password',telp_user='$telp' WHERE id_user=$iduser");
    header("location:sukseslogin.php");
    
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
                <td>Nama pengguna</td>
                <td><input type="text" name="nama_user" value="<?=$data['nama_user']?>"/></td>
            </tr>
            <tr>
                <td>Alamat email</td>
                <td><input type="text" name="email_user" value="<?=$data['email_user']?>"></textarea></td>
            </tr>
            <tr>
                <td>Kata sandi</td>
                <td><input type="password" name="password_user" value="<?=$data['password_user']?>"></textarea></td>
            </tr>
            <tr>
                <td>Nomor telepon</td>
                <td><input type="text" name="telp_user" value="<?=$data['telp_user']?>"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="tombol"/></td>
            </tr>
        </table>
        </form>

    </body>
</html>