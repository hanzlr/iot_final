<?php 
include_once "../config/database.php";

$analog_value = $_GET['analog_value'];
$lux_value = $_GET['lux_value'];
$lamp_percentage = $_GET['lamp_percentage'];

$query = "INSERT INTO sensor_data (`analog_value`, `lux_value`, `lamp_percentage`, `tanggal`, `waktu`) 
                    VALUES ($analog_value,$lux_value,$lamp_percentage, CURRENT_DATE(), CURRENT_TIME())";

$result = mysqli_query($conn, $query);

if ($result) {
    echo "Data Berhasil Ditambahkan";
} else {
    echo "Data Gagal Ditambahkan";
}
