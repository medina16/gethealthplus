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
    <h2>Notifikasi</h2>
        <table border="1" width="400">
            <?php
            $query = mysqli_query($koneksi,"SELECT * FROM notifikasi WHERE id_user = $iduser ORDER BY timestamp_notif DESC");
            while($row = mysqli_fetch_array($query))
            {
                $infopesanan = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM pesanan WHERE id_pesanan = $row[id_pesanan] "));
                ?>
                <tr>
                    <td><?php echo $row['timestamp_notif'];?></td>
                    <td>Pesanan yang kamu buat pada 
                    <?php 
                        echo $infopesanan['timestamp_pesanan'];
                        if($row['status_pesanan']==0){
                            echo " sedang dalam proses pengemasan.";
                        }
                        if($row['status_pesanan']==1){
                            echo " sedang dalam proses pengiriman.";
                        }
                        if($row['status_pesanan']==2){
                            echo " sudah selesai diproses.";
                        }
                        if($row['status_pesanan']==3){
                            echo " ditolak.";
                        }
                    ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>

    </body>
</html>
