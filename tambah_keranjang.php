<?php
include('koneksi.php');
session_start();
if($_SESSION['id_user']!=NULL){
    $iduser = $_SESSION["id_user"]; 
} else {
    header("Location:form_login.php");
}

if(isset($_GET['id_produk']))
{ 
    $id_produk = $_GET['id_produk'];
}

if(isset($_POST['tombol']))
{
    $jumlah = $_POST['jumlah'];
    $query = mysqli_query($koneksi,"INSERT INTO keranjang_belanja(id_user,id_produk,jumlah) VALUES ($iduser,$id_produk,$jumlah)");
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
                <td>Jumlah</td>
                <td><input type="number" name="jumlah"/></td>
            </tr>
                <td><input type="submit" name="tombol"/></td>
            </tr>
        </table>
        </form>

    </body>
</html>