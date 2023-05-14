<?php 
include('koneksi.php');
if(isset($_POST['tombol']))
{
    if(!isset($_FILES['gambar']['tmp_name'])){
        echo '<span style="color:red"><b><u><i>Pilih file gambar</i></u></b></span>';
    }
    else
    {
        $file_size = $_FILES['gambar']['size'];
        $file_type = $_FILES['gambar']['type'];
        if ($file_size < 2048000 and ($file_type =='image/jpeg' or $file_type == 'image/png'))
        {
            $nama = $_POST['nama_user'];
            $email = $_POST['email_user'];
            $password = $_POST['password_user'];
            $telp = $_POST['telp_user'];
            $foto = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));
            mysqli_query($koneksi,"insert into pengguna (nama_user,email_user,password_user,telp_user,foto_user) values ('$nama','$email','$password','$telp','$foto')");
            header("location:index.php");
        }
        else
        {
            echo '<span style="color:red"><b><u><i>Ukuruan File / Tipe File Tidak Sesuai</i></u></b></span>';
        }
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
                <td>Nama pengguna</td>
                <td><input type="text" name="nama_user"/></td>
            </tr>
            <tr>
                <td>Alamat email</td>
                <td><input type="text" name="email_user"></textarea></td>
            </tr>
            <tr>
                <td>Kata sandi</td>
                <td><input type="password" name="password_user"></textarea></td>
            </tr>
            <tr>
                <td>Nomor telepon</td>
                <td><input type="text" name="telp_user"></textarea></td>
            </tr>
            <tr>
                <td>Foto profil</td>
                <td><input type="file" name="gambar"/></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="tombol"/></td>
            </tr>
        </table>
        </form>

    </body>
</html>