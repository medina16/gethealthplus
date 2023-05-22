<?php 
include('koneksi.php');
session_start();
if($_SESSION['id_user']!=NULL){
		$iduser = $_SESSION["id_user"]; 
	} else {
		header("Location:form_login.php");
}
if(isset($_POST['cari'])){
    $katakunci = $_POST['katakunci'];
    $query = mysqli_query($koneksi,"SELECT * FROM produk WHERE nama_produk LIKE '%$katakunci%' OR indikasi LIKE '%$katakunci%' OR deskripsi_produk LIKE '%$katakunci%'");
    if(mysqli_num_rows($query)==0){
        $querykosong = "Maaf, hasil pencarian tidak ditemukan.";
    }
    else{
        $querykosong = 0;
    }
}
?>

<html>
    <head>
        <title></title>
    </head>
    <body>
    <form method=post action=hasil_pencarian.php enctype=multipart/form-data>
			<input type="text" name="katakunci" placeholder="Ketik nama obat atau keluhan ...">
			<input type="submit" value="Cari" name="cari" />
		</form>
        <table border="1">
            <?php
            if($querykosong){
                echo $querykosong;
            }
            else{

                while($row = mysqli_fetch_array($query))
                {
                    ?>
                    <tr>
                        <td style="vertical-align: top;"><img src="image_view_produk.php?id_produk=<?php echo $row['id_produk']; ?>" width="100"/></td>
                        <td style="vertical-align: top;">
                            <a href="detailproduk.php?id_produk=<?php echo $row['id_produk']; ?>"><?php echo $row['nama_produk']; ?></a></br>
                            Rp<?php echo $row['harga_produk']; ?>/<?php echo $row['jenis_satuan']; ?></br>
                            <?php
                            if($row['jumlah_stok']>0) echo "Stok tersedia";
                            else echo "Stok habis" 
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </body>
</html>