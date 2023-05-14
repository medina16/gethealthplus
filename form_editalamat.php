<?php 
include('koneksi.php');
session_start();
if($_SESSION['id_user']!=NULL){
		$iduser = $_SESSION["id_user"]; 
	} else {
		header("Location:form_login.php");
}

$id_alamat = $_GET['id_alamat'];
$query = mysqli_query($koneksi,"SELECT * FROM alamat WHERE id_alamat='$id_alamat' AND id_user='$iduser';");

$data = mysqli_fetch_array($query);
if(mysqli_num_rows($query) == 0) {
    header("location:form_login.php");
}

if(isset($_POST['tombol']))
{
    var_dump($_POST);
    $nama = $_POST['nama_alamat'];
    $penerima = $_POST['penerima_alamat'];
    $telp = $_POST['telp_alamat'];
    $provinsi = $_POST['provinsi_alamat'];
    $kabkot = $_POST['kabkot_alamat'];
    $kec = $_POST['kec_alamat'];
    $kel = $_POST['kel_alamat'];
    $kodepos = $_POST['kodepos_alamat'];
    $detail = $_POST['detail_alamat'];
    if( isset($_POST['favorit_alamat'])){
        mysqli_query($koneksi,"UPDATE alamat SET favorit_alamat=0 WHERE alamat.favorit_alamat=1");
        $fav = 1;
    }
    else{
        $fav = 0;
    }
    
    mysqli_query($koneksi,"UPDATE alamat SET nama_alamat='$nama', penerima_alamat='$penerima', telp_alamat='$telp', provinsi_alamat='$provinsi', kabkot_alamat='$kabkot', kec_alamat='$kec', kel_alamat='$kel', kodepos_alamat='$kodepos', detail_alamat='$detail', favorit_alamat=$fav WHERE id_alamat='$id_alamat' AND id_user='$iduser';");
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
                <td>Nama alamat</td>
                <td><input type="text" name="nama_alamat" value="<?=$data['nama_alamat']?>"/></td>
            </tr>
            <tr>
                <td>Nama penerima</td>
                <td><input type="text" name="penerima_alamat" value="<?=$data['penerima_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>No. telp penerima</td>
                <td><input type="text" name="telp_alamat" value="<?=$data['telp_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>Provinsi</td>
                <td><input type="text" name="provinsi_alamat" value="<?=$data['provinsi_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>Kota/Kabupaten</td>
                <td><input type="text" name="kabkot_alamat" value="<?=$data['kabkot_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td><input type="text" name="kec_alamat" value="<?=$data['kec_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>Kelurahan</td>
                <td><input type="text" name="kel_alamat" value="<?=$data['kel_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>Kode pos</td>
                <td><input type="text" name="kodepos_alamat" value="<?=$data['kodepos_alamat']?>"></textarea></td>
            </tr>
            <tr>
                <td>Detail alamat</td>
                <td><input type="text" name="detail_alamat" value="<?=$data['detail_alamat']?>"></textarea></td>
            </tr>
            <tr>
			<td><input type="checkbox" name="favorit_alamat" value="Ya" <?php if ($data['favorit_alamat']==1) echo "checked"?>>Jadikan alamat favorit</td>
            </tr>
            <tr>
                <td><input type="submit" name="tombol"/></td>
            </tr>
        </table>
        </form>

    </body>
</html>