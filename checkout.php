<?php
include('koneksi.php');

// ambil id user untuk session login yang sekarang
// kalo belom login bakal langsung diarahin ke form_login
session_start();
	if($_SESSION['id_user']!=NULL){
		$iduser = $_SESSION["id_user"]; 
	} else {
		header("Location:form_login.php");
}

// kalo udah klik tombol submit form (buat pesanan) yang ada di halaman ini
if(isset($_POST['pesan']))
{
    // ambil id alamat yang disubmit
    $id_alamat = $_POST['id_alamat'];

    // insert pesanan baru di table pesanan, statusnya 0 (lagi diproses)
    mysqli_query($koneksi,"INSERT into pesanan (id_user,id_alamat,status_pesanan) VALUES('$iduser','$id_alamat',0)");

    $x = 0;
    // insert semua id produk sama jumlah masing2 produk yang dipesan(disubmit ke form) ke table rincian_pesanan
    while (count($_POST["produk_pesanan"])!=$x) {
        $jumlah_pesanan = $_POST["jumlah_produk"][$x]; 
        $id_produk = $_POST["produk_pesanan"][$x];
        $jumlah_pesanan = $_POST["jumlah_produk"][$x];

        mysqli_query($koneksi,"INSERT into rincian_pesanan (id_pesanan,id_produk,jumlah_pesanan) VALUES(LAST_INSERT_ID(),'$id_produk','$jumlah_pesanan')");
	// kalo udah insert, hapus produk dari keranjang belanja
        mysqli_query($koneksi,"DELETE FROM keranjang_belanja WHERE id_produk='$id_produk' AND id_user='$iduser'");
        $x = $x + 1;
    }
    
    header("location:sukseslogin.php");
    
}

?>
<html>
    <head>
        <title></title>
    </head>
    <body>

    <h2>Checkout</h2>
     <form method="post" action="" enctype="multipart/form-data"><form method="post" action="" enctype="multipart/form-data">
            <table border="1">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <!--<th>Resep</th>-->
                </tr>
                <?php
                $total = 0; // buat nanti totalin harga pesanan
                $array_id_produk = array(); // buat nampung id produk dari semua produk yang bakal dipesen
                $array_jumlah_produk = array(); // buat nampung jumlah yang dipesan buat masing2 produk
		    
		// buat nampilin semua produk yang ada di keranjang belanja user
                $query = mysqli_query($koneksi,"SELECT * FROM keranjang_belanja WHERE id_user=$iduser");
                while($row = mysqli_fetch_array($query))
                {   
                    $id_produk = $row['id_produk'];
                    array_push($array_id_produk, $id_produk); // masukin id produk di satu baris ke array id produk
                    array_push($array_jumlah_produk, $row['jumlah']); // masukin jumlah produk di satu baris ke array jumlah produk
                    
		// bikin baris di dalem tabel html untuk setiap produk di keranjang belanja
                    if(in_array($row['id_produk'], $_POST["produk_checkout"])){ // kalo gasalah(??) ini buat kalo misalnya bisa ceklis produk di keranjang belanja, tapi gatau deh lupa
			 // buat ngambil nama produk sama harga produk berdasarkan id produk yang lagi diperiksa
                        $infoproduk = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM produk WHERE id_produk=$id_produk;"));
                        ?>
                        <tr>
                            <td style="vertical-align: top;"><img src="image_view_produk.php?id_produk=<?php echo $row['id_produk']; ?>" width="100"/></td>
                            <td style="vertical-align: top;"><?php echo $infoproduk['nama_produk']; ?></td>
                            <td style="vertical-align: top;"><?php echo $row['jumlah']; ?> <?php echo $infoproduk['jenis_satuan']; ?></td>
                            <td style="vertical-align: top;"><?php echo $row['jumlah']*$infoproduk['harga_produk']; ?></td>
                            <?php $total = $total + $row['jumlah']*$infoproduk['harga_produk']; ?> <!-- buat ngetotalin harga -->
                            <!--<?php if($infoproduk['perlu_resep']==1){
                                echo"<td><input type=file name=gambar/></td>";
                            }else{
                                echo"<td>Tidak butuh</td>";
                            }
                            ?>-->
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <b>Total: </b> <?php echo $total; ?> </br>
            </br>
            <b>Alamat Pengiriman:</b></br>
            <?php 
                $query = mysqli_query($koneksi, "SELECT * FROM alamat WHERE id_user=$iduser");
                $dataalamat = mysqli_fetch_array($query);
                echo "<u>".$dataalamat['nama_alamat']."</u></br>";
                echo $dataalamat['penerima_alamat']."</br>";
                echo $dataalamat['telp_alamat']."</br>";
                echo $dataalamat['provinsi_alamat']."</br>";
                echo $dataalamat['kabkot_alamat']."</br>";
                echo $dataalamat['kec_alamat']."</br>";
                echo $dataalamat['kodepos_alamat']."</br>";
                echo $dataalamat['detail_alamat']."</br>";
                // buat ngesubmit semua id produk di dalem array id produk ke array produk pesanan (di $_POST)
                foreach($array_id_produk as $id_produk_tunggal){
                    echo '<input type="hidden" name="produk_pesanan[]" value="'. $id_produk_tunggal. '">';
                }
                // buat ngesubmit jumlah utk masing2 produk di array jumlah produk ke array jumlah pesanan (di $_POST)
                foreach($array_jumlah_produk as $jumlah_produk){
                    echo '<input type="hidden" name="jumlah_produk[]" value="'. $jumlah_produk. '">';
                }
            ?>
            </br>
            <input type="hidden" name="id_alamat" value=<?=$dataalamat['id_alamat']?>>
            <input type="submit" value="Buat pesanan" name="pesan" />
    </form>
    
    </body>
</html>
