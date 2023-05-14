<?php
session_start();
if($_SESSION['id_user']!=NULL){
		$iduser = $_SESSION["id_user"]; 
	} else {
		header("Location:form_login.php");
}
if(isset($_GET['id_produk']))
{
    include('koneksi.php');
    $id_produk = $_GET['id_produk'];
    $query = mysqli_query($koneksi,"DELETE FROM keranjang_belanja WHERE id_produk='$id_produk' AND id_user='$iduser'");
}
header('location:sukseslogin.php');
?>