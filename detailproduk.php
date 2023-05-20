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
    $query = mysqli_query($koneksi,"SELECT * FROM produk WHERE id_produk='$id_produk'");
    $row = mysqli_fetch_array($query);
}


?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <h2><?php echo $row['nama_produk']; ?></h2>
        <b>Rp<?php echo $row['harga_produk']; ?>/<?php echo $row['jenis_satuan']; ?></b></br>
        <b>Stok tersisa <?php echo $row['jumlah_stok']; ?> <?php echo $row['jenis_satuan']; ?></b></br>
        
        <img src="image_view_produk.php?id_produk=<?php echo $row['id_produk']; ?>" width="100"/></br>
        
        <h3>Deskripsi</h3>
        <p><?php echo $row['deskripsi_produk']; ?></p>
        <h3>Indikasi</h3>
        <p><?php echo $row['indikasi']; ?></p>
        <h3>Kontraindikasi</h3>
        <p><?php echo $row['kontraindikasi']; ?></p>
        <h3>Komposisi</h3>
        <p><?php echo $row['komposisi']; ?></p>
        <h3>Aturan pakai</h3>
        <p><?php echo $row['aturan_pakai']; ?></p>
        <h3>Efek samping</h3>
        <p><?php echo $row['efek_samping']; ?></p>
        <h3>Peringatan dan perhatian</h3>
        <p><?php echo $row['peringatan_perhatian']; ?></p>

        <a href="tambah_keranjang.php?id_produk=<?php echo $row['id_produk']; ?>">Tambah ke keranjang</a></td>

        <table border="1">
            <?php
            $queryulasan = mysqli_query($koneksi,"SELECT * FROM ulasan WHERE id_produk=$id_produk ORDER BY timestamp_ulasan DESC");
            if(mysqli_num_rows($queryulasan) != 0) {
                // kalo ada ulasan tampilin rata2 ulasan sama ulasan2nya
                $penilaian = mysqli_query($koneksi,"SELECT (AVG(rating)) AS avg_rating FROM ulasan WHERE id_produk = $id_produk");
                $berapa = mysqli_fetch_array($penilaian);
                echo"<p></br><b>Overall Rating:</b> ".$berapa['avg_rating']."</p>";
            }
            while($row = mysqli_fetch_array($queryulasan))
            {
                $id_pengulas = $row['id_user'];
                $queryuser = mysqli_query($koneksi,"SELECT nama_user FROM pengguna WHERE id_user=$id_pengulas");
                $fetchnamauser = mysqli_fetch_array($queryuser);
                ?>
                <tr>
                    <td>
                        <img src="image_view_user.php?id_user=<?php echo $row['id_user']; ?>" width="50"/></br>
                        <?php echo $fetchnamauser['nama_user']; ?></br>
                        <?php echo $row['timestamp_ulasan']; ?></br>
                        <?php echo $row['rating']; ?> bintang
                    </td>
                    <td><?php echo $row['review']; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>