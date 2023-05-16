<?php
include('koneksi.php');
session_start();
	if($_SESSION['id_user']!=NULL){
		$iduser = $_SESSION["id_user"]; 
	} else {
		header("Location:form_login.php");
}


?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <!--https://www.warungbelajar.com/upload-dan-menampilkan-blob-image-dengan-php-dan-mysql.html-->
        <h2>Sudah terlogin</h2>
        <a href="logout.php">Logout</a>

        <h2>Data Diri</h2>
        <a href="form_editakun.php">Edit</a>
        <a href="delete_user.php?id_user=<?php echo $iduser; ?>">Hapus akun</a>
        <table border="1">
            <tr>
                <th>Foto profil</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Password</th>
                <th>No.telp</th>
            </tr>
            <?php
            $query = mysqli_query($koneksi,"SELECT * FROM pengguna WHERE id_user = $iduser");
            while($row = mysqli_fetch_array($query))
            {
                ?>
                <tr>
                    <td><img src="image_view_user.php?id_user=<?php echo $row['id_user']; ?>" width="100"/></td>
                    <td><?php echo $row['nama_user']; ?></td>
                    <td><?php echo $row['email_user']; ?></td>
                    <td><?php echo $row['password_user']; ?></td>
                    <td><?php echo $row['telp_user']; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

        <h2>List Alamat</h2>
        <a href="form_alamat.php">Tambah alamat</a>
        <table border="1" width="100">
            <tr>
                <th>Nama alamat</th>
                <th>Nama penerima</th>
                <th>No telp. penerima</th>
                <th>Provinsi</th>
                <th>Kota/Kabupaten</th>
                <th>Kecamatan</th>
                <th>Kelurahan</th>
                <th>Kode pos</th>
                <th>Detail alamat</th>
                <th>Favorit?</th>
            </tr>
            <?php
            $query = mysqli_query($koneksi,"SELECT * FROM alamat WHERE id_user = $iduser ORDER BY favorit_alamat DESC");
            while($row = mysqli_fetch_array($query))
            {
                ?>
                <tr>
                    <td><?php echo $row['nama_alamat']; ?></td>
                    <td><?php echo $row['penerima_alamat']; ?></td>
                    <td><?php echo $row['telp_alamat']; ?></td>
                    <td><?php echo $row['provinsi_alamat']; ?></td>
                    <td><?php echo $row['kabkot_alamat']; ?></td>
                    <td><?php echo $row['kec_alamat']; ?></td>
                    <td><?php echo $row['kel_alamat']; ?></td>
                    <td><?php echo $row['kodepos_alamat']; ?></td>
                    <td><?php echo $row['detail_alamat']; ?></td>
                    <td><?php echo $row['favorit_alamat']; ?></td>
                    <td>
                        <ul>
                        <li><a href="favorit_alamat.php?id_alamat=<?php echo $row['id_alamat']; ?>">Jadikan alamat favorit</a></li>
                        <li><a href="delete_alamat.php?id_alamat=<?php echo $row['id_alamat']; ?>">Delete</a></li>
                        <li><a href="form_editalamat.php?id_alamat=<?php echo $row['id_alamat']; ?>">Edit</a></li>
                        </ul>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>

        <h2>Keranjang belanja</h2>
        <form method="post" action="checkout.php" enctype="multipart/form-data">
        <table border="1">
            <tr>
                <th>Foto</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
            <?php
            $array_id_produk = array();
            $total = 0;
            $query = mysqli_query($koneksi,"SELECT * FROM keranjang_belanja WHERE id_user=$iduser");
            while($row = mysqli_fetch_array($query))
            {   
                $id_produk = $row['id_produk'];
                $infoproduk = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM produk WHERE id_produk=$id_produk;"));
                array_push($array_id_produk, $id_produk);
                ?>
                <tr>
                    <td style="vertical-align: top;"><img src="image_view_produk.php?id_produk=<?php echo $row['id_produk']; ?>" width="100"/></td>
                    <td style="vertical-align: top;"><?php echo $infoproduk['nama_produk']; ?></td>
                    <td style="vertical-align: top;"><?php echo $row['jumlah']; ?> <?php echo $infoproduk['jenis_satuan']; ?></td>
                    <td style="vertical-align: top;"><?php echo $row['jumlah']*$infoproduk['harga_produk']; ?></td>
                    <?php $total = $total + $row['jumlah']*$infoproduk['harga_produk']; ?>
                    <td><a href="delete_darikeranjang.php?id_produk=<?php echo $row['id_produk']; ?>">Delete</a></li></td>
                </tr>
                <?php
            }
            ?>
        </table>
        <b>Total: </b>Rp<?php echo $total; ?>
        <?php
        foreach($array_id_produk as $id_produk_tunggal)
        {
            echo '<input type="hidden" name="produk_checkout[]" value="'. $id_produk_tunggal. '">';
        }
        ?>
        <input type="submit" value="Checkout" name="checkout" />
        </form>

        <h2>Pesanan</h2>
        <table border="1">
            <tr>
                <th>Pesanan</th>
                <th>Detail</th>
            </tr>
            <?php
            $array_id_produk = array();
            $total = 0;
            $query = mysqli_query($koneksi,"SELECT * FROM pesanan WHERE id_user=$iduser");
            while($row = mysqli_fetch_array($query))
            {   
                $id_pesanan = $row['id_pesanan'];
                $tanggal_pesanan = $row['timestamp_pesanan'];
                ?>
                <tr>
                    <td style="vertical-align: top;">
                        <?php echo $row['id_pesanan']; ?></br>
                        <?php echo $row['timestamp_pesanan']; ?></br>
                        <?php echo $row['status_pesanan']; ?></br>
                   </td>
                   <td style="vertical-align: top;">
                <?php
                $queryprodukpesanan = mysqli_query($koneksi,"SELECT * FROM rincian_pesanan WHERE id_pesanan=$id_pesanan");
                while($rowproduk = mysqli_fetch_array($queryprodukpesanan)){
                    $id_produk = $rowproduk['id_produk'];
                    $infoproduk = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM produk WHERE id_produk=$id_produk;"));
                    ?>
                    
                    <img src="image_view_produk.php?id_produk=<?php echo $rowproduk['id_produk']; ?>" width="100"/></br>
                    <?php echo $infoproduk['nama_produk']; ?></br>
                    <?php echo $rowproduk['jumlah_pesanan']; ?> <?php echo $infoproduk['jenis_satuan']; ?></br>
                    Rp<?php echo $rowproduk['jumlah_pesanan']*$infoproduk['harga_produk']; ?></br>
                    <?php $total = $total + $rowproduk['jumlah_pesanan']*$infoproduk['harga_produk']; ?></br>
                    <?php
                }
                ?>
                <b>Total: </b>Rp<?php echo $total; ?>
                </td>
                </tr>
                <?php
            }
            ?>
        </table>

        <h2>Produk</h2>
        <table border="1" width="900">
            <tr>
                <th></th>
                <th>Foto</th>
                <th>Golongan</th>
                <th>Nama</th>
                <th>Detail</th>
                <th>Harga Satuan</th>
                <th>Perlu resep</th>
            </tr>
            <?php
            $query = mysqli_query($koneksi,"SELECT * FROM produk");
            while($row = mysqli_fetch_array($query))
            {
                ?>
                <tr>
                    <td style="vertical-align: top;"><a href="tambah_keranjang.php?id_produk=<?php echo $row['id_produk']; ?>">Tambah ke keranjang</a></td>
                    <td style="vertical-align: top;"><img src="image_view_produk.php?id_produk=<?php echo $row['id_produk']; ?>" width="100"/></td>
                    <td style="vertical-align: top;"><?php echo $row['id_golongan']; ?></td>
                    <td style="vertical-align: top;"><?php echo $row['nama_produk']; ?></td>
                    <td style="vertical-align: top;">
                        <b>Deskripsi</b></br>
                        <?php echo $row['deskripsi_produk']; ?></br>
                        <b>Indikasi</b></br>
                        <?php echo $row['indikasi']; ?></br>
                        <b>Kontraindikasi</b></br>
                        <?php echo $row['kontraindikasi']; ?></br>
                        <b>Komposisi</b></br>
                        <?php echo $row['komposisi']; ?></br>
                        <b>Aturan pakai</b></br>
                        <?php echo $row['aturan_pakai']; ?></br>
                        <b>Efek samping</b></br>
                        <?php echo $row['efek_samping']; ?></br>
                        <b>Peringatan dan perhatian</b></br>
                        <?php echo $row['peringatan_perhatian']; ?>
                    <td style="vertical-align: top;">Rp<?php echo $row['harga_produk']; ?>/<?php echo $row['jenis_satuan']; ?></td>
                    <td style="vertical-align: top;"><?php echo $row['perlu_resep']; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>

    </body>
</html>
