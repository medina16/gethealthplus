<?php 
include ("koneksi.php");

session_start();
    if($_SESSION['id_user']!=NULL){
            $iduser = $_SESSION["id_user"]; 
        } else {
            header("Location:form_login.php");
    }

if(isset($_POST['ulas'])){
    $id_produk = $_POST['id_produk'];
    $id_pesanan = $_POST['id_pesanan'];
    $query = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = $id_produk");
    $infoproduk = mysqli_fetch_array($query);
}

if(isset($_POST['submit'])){
    $id_produk = $_POST['id_produk'];
    $iduser = $_POST['id_user'];
    $id_pesanan = $_POST['id_pesanan'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $query = mysqli_query($koneksi, "INSERT INTO ulasan(id_pesanan,id_user,id_produk,rating,review) VALUES('$id_pesanan','$iduser','$id_produk','$rating','$review')");
    header("Location:sukseslogin.php");
} 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Laundry - Beri Ulasan</title>
	<link rel="stylesheet" href="stylesheet.css">
	<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,700;1,600&display=swap" rel="stylesheet">
</head>

<body>
	<header>
		<div class="container">
			<h2>Beri Ulasan</h2>
		</div>
	</header>

    <?php echo "$infoproduk[nama_produk]";?>
    <img src="image_view_produk.php?id_produk=<?php echo $infoproduk['id_produk']; ?>" width="300"/>


    
	<form method="post" action="" enctype="multipart/form-data">
		<fieldset>
		<input type="hidden" name="id_produk" value="<?=$id_produk?>"/>
		<input type="hidden" name="id_user" value="<?=$iduser?>"/>
        <input type="hidden" name="id_pesanan" value="<?=$id_pesanan?>"/>
		<p>
			<label for="rating">Rating</label></br>
			<label><input type="radio" name="rating" value="1">1</label>
			<label><input type="radio" name="rating" value="2">2</label>
			<label><input type="radio" name="rating" value="3">3</label>
			<label><input type="radio" name="rating" value="4">4</label>
			<label><input type="radio" name="rating" value="5">5</label>
		</p>
		<p>
			<label for="review">Review</label></br>
			<textarea name="review" maxlength="250"></textarea>
		</p>
		<p>
			<input type="submit" value="Submit" name="submit" />
		</p>
		

		</fieldset>
	</form>

	</body>
</html>
