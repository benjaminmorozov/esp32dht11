<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
global $err;
function request($pin) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://blynk.cloud/external/api/get?token=***&$pin",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache"
        ),
    ));
    $response = curl_exec($curl);
    return $response;
}
function isUp() {
    global $err;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://blynk.cloud/external/api/isHardwareConnected?token=***",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache"
        ),
    ));
    $response = curl_exec($curl);
    if($response == "true") {
        return '<i class="fa-solid fa-check"></i> online';
    } else {
        $err = "offline";
        return '<i class="fa-solid fa-stop"></i> offline';
    }
}
function offline() {
    global $err;
    if ($err == "offline") {
        echo '<div class="page">
        <div class="content javascript">
            <div class="inline">
            <div class="card" style="margin-bottom: 0 !important">
                <div class="card-body">
                    <div class="lead">Zariadenie nie je dostupné</div>
                    <h2 class="card-title">Skontrolujte, či je zariadenie zapnuté</h2>
                    <p class="small text-muted">(isUp() probe)</p>
                </div>
            </div>
        </div>
        <div class="lead" style="margin-top:3vh !important">Tu sú zatiaľ posledné zaznamenané dáta:</div>
    </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>esp32</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="namec">
        <div class="bl"></div>
        <h1 class="title">esp32 <i class="fa-solid fa-microchip fa-beat" style="--fa-animation-duration: 5s;"></i></h1>
    </div>
    <div class="name">
        <h3 id="network"><i class="fa-solid fa-wifi"></i> <?php echo request("V0") ?></h3>
        <h3><i class="fa-solid fa-toolbox"></i> 2.0.0+20230220</h3>
        <h3> <?php echo isUp() ?></h3>
        <h3 id="time"><i class="fa-solid fa-clock"></i> <?php echo date("H:i:s") ?></h3>
    </div>

    <hr class="dashed">

    <div class="page">
        <h2># základné informácie</h2>
        <?php offline() ?>
        <div class="content javascript">
            <div class="inline">
            <div class="card">
                <div class="card-body">
                    <div class="lead">Teplota [°C]</div>
                    <h2 class="card-title" id="reload1"><?php echo request("V1") ?></h2>
                    <p class="small text-muted">(V1)</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="lead">Vlhkosť vzduchu [%]</div>
                    <h2 class="card-title" id="reload2"><?php echo request("V2") ?></h2>
                    <p class="small text-muted">(V2)</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="lead">Rosný bod [°C]</div>
                    <h2 class="card-title" id="reload3"><?php echo request("V3") ?></h2>
                    <p class="small text-muted">(V3)</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="lead">Grafické zobrazenie dát</div>
                <h2 class="card-title"><canvas id="myChart"></canvas></h2>
                <p class="small text-muted">([v1,v2,v3]/t)</p>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    setInterval(function() {
        for (i = 1; i < 5; i++) {
            $("#reload"+i).load(window.location.href + " #reload"+i);
        }
        $("#time").load(window.location.href + " #time");
        $("#network").load(window.location.href + " #network");
    }, 1000);
});

const ctx = document.getElementById('myChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<script src="https://kit.fontawesome.com/34a3fa1806.js" crossorigin="anonymous"></script>
</html>
