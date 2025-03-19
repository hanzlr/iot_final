<?php include_once "layout/header.php"; ?>
<?php include_once "layout/navbar.php"; ?>

<div class="container mt-5">
    <h2 class="text-center"><strong>Data Tabel</strong></h2>

    <table class="table mt-4 text-center table-bordered border-dark">
        <thead class="table-dark">
            <tr>
                <th scope="col">Nomor</th>
                <th scope="col">Analog Value</th>
                <th scope="col">Lux Value</th>
                <th scope="col">Lamp Percentage</th>
                <th scope="col">Tanggal</th>
                <th scope="col">Waktu</th>
            </tr>
        </thead>
        <tbody>
       

        <?php 
        include_once "config/database.php";
        $query = "SELECT * FROM sensor_data ORDER BY nomor DESC LIMIT 20";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_array($result)){


        ?>
            <tr>
                <th scope="row">
                    <?php echo $row ['nomor']; ?>
                </th>
                <td><?php echo $row ['analog_value']; ?></td>
                <td><?php echo $row ['lux_value']. " lx"; ?></td>
                <td><?php echo $row ['lamp_percentage']. " %"; ?></td>
                <td><?php echo $row ['tanggal']; ?></td>
                <td><?php echo $row ['waktu']; ?></td>
            </tr>
           
            <?php } ?>
        </tbody>
    </table>
    <br>
    <br>
    


</div>




<?php include_once "layout/footer.php"; ?>