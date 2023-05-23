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
    
    if(isset($_POST['kategori']) && $_POST['kategori']!=0) {
        $kategori = $_POST['kategori'];
        $filter_kategori = " AND id_kategori=$kategori";
    }
    else{
        $filter_kategori = "";
    }

    if(isset($_POST['hargamin']) && $_POST['hargamin']!="") {
        $hargamin = $_POST['hargamin'];
        $filter_hargamin = " AND harga_produk>=$hargamin";
    }
    else{
        $filter_hargamin = "";
    }

    if(isset($_POST['hargamax']) && $_POST['hargamax']!="") {
        $hargamax = $_POST['hargamax'];
        $filter_hargamax = " AND harga_produk<=$hargamax";
    }
    else{
        $filter_hargamax = "";
    }

    if(isset($_POST['rating'])) {
        $rating = $_POST['rating'];
        $filter_rating = " AND rating>=$rating";
    }
    else{
        $filter_rating = "";
    }
    
    $query = mysqli_query($koneksi,"SELECT * FROM produk join (SELECT id_produk,(AVG(rating)) AS rating FROM ulasan GROUP BY id_produk) AS rating ON rating.id_produk=produk.id_produk WHERE (nama_produk LIKE '%$katakunci%' OR indikasi LIKE '%$katakunci%' OR deskripsi_produk LIKE '%$katakunci%')".$filter_kategori.$filter_hargamax.$filter_hargamin.$filter_rating);
    if(mysqli_num_rows($query)==0){
        $querykosong = "Maaf, hasil pencarian tidak ditemukan.";
    }
    else if($katakunci=="" && $filter_kategori==""){
        $querykosong = "Silakan masukan kata kunci pencarian atau pilih kategori.";
    }
}

if(isset($_POST['nofilter'])){
    $katakunci = $_POST['katakunci'];
    $query = mysqli_query($koneksi,"SELECT * FROM produk WHERE (nama_produk LIKE '%$katakunci%' OR indikasi LIKE '%$katakunci%' OR deskripsi_produk LIKE '%$katakunci%')");
    if(mysqli_num_rows($query)==0){
        $querykosong = "Maaf, hasil pencarian tidak ditemukan.";
    }
    else if($katakunci==""){
        $querykosong = "Silakan masukan kata kunci pencarian atau pilih kategori.";
    }
}

?>

<html>
    <head>
        <title></title>
    </head>
    <body>
    <form method=post action=hasil_pencarian.php enctype=multipart/form-data>
		<input type="text" name="katakunci" placeholder="Ketik nama obat atau keluhan ..." value="<?php if(isset($katakunci)) echo $katakunci; ?>"/>
		<input type="submit" value="Cari" name="cari" /></td></br>
        <div style="border:1px solid black; width:350px;">
            <b>FILTER</b></br>
            <input type="submit" value="Lepas filter" name="nofilter" /></td></br>
            <b>Kategori</b></br>
            <select name="kategori">
                <option value="" disabled selected hidden>Pilih kategori...</option>
                <option value="0">Bebas</option>
                <?php 
                    $querykategori = mysqli_query($koneksi,"SELECT * FROM kategori");
                    while($row = mysqli_fetch_array($querykategori)){
                        echo "<option value=$row[id_kategori]";
                        if(isset($kategori) && $kategori==$row['id_kategori']) echo " selected";
                        echo ">$row[nama_kategori]</option>";
                    }
                ?>
			</select></br>
            <b>Harga</b></br>
            Rp <input type="number" name="hargamin" min="0" placeholder="minimal ..." style="width:100px;" <?php if(isset($hargamin)) echo "value=$hargamin"?>>
             - Rp <input type="number" name="hargamax" min="0" placeholder="maksimal ..." style="width:100px;"<?php if(isset($hargamax)) echo "value=$hargamax"?>></br>
            <b>Rating</b></br>
            <select name="rating">
                <option value="" disabled selected hidden>Pilih rating...</option>
                <option value="0"<?php if(isset($rating) && $rating==0) echo " selected";?>>Bebas</option>
                <option value="5"<?php if(isset($rating) && $rating==5) echo " selected";?>>5 ★</option>
                <option value="4"<?php if(isset($rating) && $rating==4) echo " selected";?>>≥4 ★</option>
                <option value="3"<?php if(isset($rating) && $rating==3) echo " selected";?>>≥3 ★</option>
                <option value="2"<?php if(isset($rating) && $rating==2) echo " selected";?>>≥2 ★</option>
                <option value="1"<?php if(isset($rating) && $rating==1) echo " selected";?>>≥1 ★</option>
            </select>
        </div>
	</form>
        <table border="1">
            <?php
            if(isset($querykosong)){
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
