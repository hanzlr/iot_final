<?php include_once "layout/header.php"; ?>
<?php include_once "layout/navbar.php"; ?>

<div class="container mt-5">
    <h2 class="text-center"> DATA SENSOR LAMPU</h2>

    <div class="container">
        <!-- LOGO -->
        <div class="row justify-content-center mt-3">
            <div class="col-12 text-center">
                <div class="card text-bg-dark shadow-sm border-0 p-3" style="width: 100%; height: 200px;">
                    <img src="link\teknik_elektroTE.png" class="card-img-top img-fluid" alt="LOGO UNIVERSITAS"
                        style="object-fit: contain; width: 100%; height: 100%;">
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-between mt-4">
            <!-- Analog Value -->
            <div class="col">
                <div class="card h-100 shadow-sm border-3 border-dark p-3" style="min-height: 304px;">
                    <div class="card-body">
                        <p class="card-header fs-3">Analog Value</p>
                        <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%;">
                            <h1 class="text-center" id="analog_value" style="margin: 0;"></h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lux Value -->
            <div class="col">
                <div class="card h-100 shadow-sm border-3 border-dark p-3" style="min-height: 304px;">
                    <div class="card-body">
                        <p class="card-header fs-3">Lux Value</p>
                        <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%;">
                            <h1 class="text-center" id="lux_value" style="margin: 0;"></h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col d-flex flex-column ">
                <!-- Lamp Percentage -->
                <div class="card mb-3 border-3 border-dark" style="min-height: 100px;">
                    <div class="card-body">
                        <p class="card-header fs-4">Lamp Percentage</p>
                        <h3 class="card-body text-center" id="lamp_percentage"></h3>
                    </div>
                </div>

                <!-- Logo No Background -->
                <div class="card border-3 border-dark" style="min-height: 100px;">
                    <div class="card-body " style="width: 100%; height: 100%;">
                        <img src="link\logo-no-background.png" class="card-img-top img-fluid" alt="WATERMARK"
                            style="object-fit: contain; width: 100%; max-height: 100px;">
                    </div>
                </div>
            </div>


        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-4 ">
            <!-- Jumlah Data -->
            <div class="col">
                <div class="card h-100 shadow-sm p-3 border-3 border-dark">
                    <div class="card-body">
                        <p class="card-header fs-4">Jumlah Data</p>
                        <h3 class="card-body text-center" id="nomor"></h3>
                    </div>
                </div>
            </div>

            <!-- Waktu Terakhir -->
            <div class="col">
                <div class="card h-100 shadow-sm p-3 border-3 border-dark">
                    <div class="card-body">
                        <p class="card-header fs-4">Waktu Terakhir Update</p>
                        <h3 class="card-body text-center" id="waktu"></h3>
                    </div>
                </div>
            </div>

            <!-- Tanggal Terakhir -->
            <div class="col">
                <div class="card h-100 shadow-sm p-3 border-3 border-dark">
                    <div class="card-body">
                        <p class="card-header fs-4">Tanggal Terakhir Update</p>
                        <h3 class="card-body text-center" id="tanggal"></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- ThingSpeak -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 text-center border-3 border-dark">
                <iframe width="440" height="250" style="border: 1px solid #cccccc;"
                    src="https://thingspeak.com/channels/2731279/charts/1?bgcolor=%23FCFAEE&color=%23B8001F&dynamic=true&results=60&title=ANALOG+VALUE&type=line">
                </iframe>
            </div>
            <div class="col-md-6 text-center">
                <iframe width="440" height="250" style="border: 1px solid #cccccc;"
                    src="https://thingspeak.com/channels/2731279/charts/2?bgcolor=%23FCFAEE&color=%23B8001F&dynamic=true&results=60&title=LUX&type=line">
                </iframe>
            </div>
        </div>
    </div>

</div>
<br>
<br>
<script>
    //fetch data
    function fetchData() {
        fetch("http://127.0.0.1/iot_final/api/read.php")
            .then(response => response.text())
            .then(data => {
                
                const jsonData = JSON.parse(data.replace(/^data: /, ''));

                
                console.log("Parsed Data: ", jsonData);

                document.getElementById("nomor").innerText = jsonData.nomor;
                document.getElementById("analog_value").innerText = jsonData.analog_value;
                document.getElementById("lux_value").innerText = jsonData.lux_value + " lx";
                document.getElementById("lamp_percentage").innerText = jsonData.lamp_percentage + " %";
                document.getElementById("tanggal").innerText = jsonData.tanggal;
                document.getElementById("waktu").innerText = jsonData.waktu;
            })
            .catch(error => {
                console.error("Error fetching data: ", error);
            });
    }

    setInterval(fetchData, 2000); //2s

    fetchData();
</script>


<?php include_once "layout/footer.php"; ?>