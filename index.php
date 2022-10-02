<?php
require('./config/confg.php');

$objectif = 1000;
$objectif2 = 2500;
$objectif3 = 5000;

session_start();
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$servername = DB_SERVER_NAME;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$dbname = DB_DATABASE;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM par_jour ORDER BY date DESC";
$result = $conn->query($sql);

$total_stehcas = 0;
$today_set = 0;

$last_date = "";
$last_les = "";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $today = intval($row["les"]);
        $total_stehcas += $today;
        if($today_set !== true){
            $last_date = $row["date"];
            $last_les = $row["les"];

            $today_set = true;
        }
    }
    $stehcas_quot = $total_stehcas/$result->num_rows;
}
$moy_lun = 0;
            $sac_lun = 0;

            $moy_mar = 0;
            $sac_mar = 0;

            $moy_mer = 0;
            $sac_mer = 0;

            $moy_jeu = 0;
            $sac_jeu = 0;

            $moy_ven = 0;
            $sac_ven = 0;

            $result = $conn->query("SELECT * FROM par_jour WHERE jour = 'lun'");
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $sac_lun += $row["les"];
                }
                $moy_lun = $sac_lun / $result->num_rows;

            }

            $result = $conn->query("SELECT * FROM par_jour WHERE jour = 'mar'");
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $sac_mar += $row["les"];
                }
                $moy_mar = $sac_mar / $result->num_rows;

            }

            $result = $conn->query("SELECT * FROM par_jour WHERE jour = 'mer'");
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $sac_mer += $row["les"];
                }
                $moy_mer = $sac_mer / $result->num_rows;
            }

            $result = $conn->query("SELECT * FROM par_jour WHERE jour = 'jeu'");
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $sac_jeu += $row["les"];
                }
                $moy_jeu = $sac_jeu / $result->num_rows;

            }

            $result = $conn->query("SELECT * FROM par_jour WHERE jour = 'ven'");
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $sac_ven += $row["les"];
                }
                $moy_ven = $sac_ven / $result->num_rows;
            }

            ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>L'entonnoir à Sel &bull; <?= $last_date ?></title>

    <meta property="og:image:url" content="https://www.epicerie-pro.com/images/Image/https-www-epicerie-pro-com-images-Image-sel-dosette-1.jpg">
    <meta property="og:title" content="L'entonnoir à Sel" />
    <meta property="og:description" content="On est sur la Sel-lette, ta pigé ? Viens collecter du Sel avec nous et remporte ton brassar ;)" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://0x454d505459.github.io/les-sels/" />
</head>
<body>
    <main>
        <div class="card">
            <div class="percent" style="--color: #00ff7f;--prg: <?= round(($total_stehcas*0.8)/($objectif/100), 2) ?>;">
                <svg>
                    <circle cx="70" cy="70" r="70"></circle>
                    <circle cx="70" cy="70" r="70"></circle>
                </svg>
                <div class="number">
                    <h2><?= round(($total_stehcas*0.8)/($objectif/100), 2) ?><span>%</span></h2>
                    <p>Objectif</p>
                </div>
            </div>
        </div>
        <div class="card">
            <h2><?= $total_stehcas ?></h2>
            <p>Nombre de sachets</p>
        </div>
        <div class="card">
            <h2>5 Sept.</h2>
            <p>Démarage</p>
        </div>
        <div class="card">
            <h2>1000g</h2>
            <p>Objectif</p>
        </div>
        <div class="card">
            <h2><?= $total_stehcas*0.8 ?>g</h2>
            <p>Poids net</p>
        </div>
        <div class="emptycard">

        </div>
        <div class="card">
            <h2><?= round($stehcas_quot) ?></h2>
            <p>Moyenne quotidienne de sachets</p>
        </div>
        <div class="card">
            <h2><?= $objectif-(round($total_stehcas*0.8)) ?></h2>
            <p>Nombre de sachets restants</p>
        </div>
        <div class="card">
            <h2><?= $objectif2 ?>g</h2>
            <p>Prochain objectif</p>
        </div>
        
    </main>
    <div class="graphs">
        <canvas id="graph1" style="width:90%;max-height: 90%;margin-right: 10;"></canvas>

    </div>
    <div class="graphs">
        <canvas id="graph2" style="width:90%;max-height: 90%;margin-right: 10;"></canvas>
    </div>
    <main>
        <div class="card">
            <h2><?= round($moy_lun)?></h2>
            <p>Moyenne de sachets le lundi</p>
        </div>
        <div class="card">
            <h2><?= round($moy_mar)?></h2>
            <p>Moyenne de sachets le mardi</p>
        </div>
        <div class="card">
            <h2><?= round($moy_mer)?></h2>
            <p>Moyenne de sachets le mercredi</p>
        </div>
        <div class="card">
            <h2><?= round($moy_jeu)?></h2>
            <p>Moyenne de sachets le jeudi</p>
        </div>
        <div class="card">
            <h2><?= round($moy_ven)?></h2>
            <p>Moyenne de sachets le vendredi</p>
        </div>

        <?php
            $semaine = array("dimanche","lundi","mardi","mercredi","jeudi", "vendredi","samedi");

            $time = time();
            $time_collect = time();

            $prev_lundi = 0;
            $prev_mardi = 0;
            $prev_mercredi = 0;
            $prev_jeudi = 0;
            $prev_vendredi = 0;
            $prev_we = 0;

            $prev_actual = $total_stehcas*0.8;
            while($prev_actual <= $objectif) {
                
                if($semaine[gmdate('w',$time)] == "dimanche" || $semaine[gmdate('w',$time)] == "samedi"){
                    $prev_we = floatval($prev_we) + 0.5;
                    $time += 86400;
                } else {
                    $time_collect += 86400;
                    if($semaine[gmdate('w',$time)] == "lundi") {
                        $prev_lundi++;
                        $prev_actual += $moy_lun;
                        $time += 86400;
                    } else if($semaine[gmdate('w',$time)] == "mardi") {
                        $prev_mardi++;
                        $prev_actual += $moy_lun;
                        $time += 86400;
                    } else if($semaine[gmdate('w',$time)] == "mercredi") {
                        $prev_mercredi++;
                        $prev_actual += $moy_lun;
                        $time += 86400;
                    } else if($semaine[gmdate('w',$time)] == "jeudi") {
                        $prev_jeudi++;
                        $prev_actual += $moy_lun;
                        $time += 86400;
                    } else if($semaine[gmdate('w',$time)] == "vendredi") {
                        $prev_vendredi++;
                        $prev_actual += $moy_lun;
                        $time += 86400;
                    }

                }

            }

            ?>
            <div class="emptycard">

            </div>
            <div class="emptycard">

            </div>
            <div class="card">
                <h2><?= gmdate('j M', $time_collect) ?>. <?= gmdate('Y', $time_collect) ?></h2>
                <p>Fin estimée avec <?= round($prev_actual) ?> sachets</p>
            </div>
    </main>
    <script>
        const dates = [
            <?php
            $sql = "SELECT * FROM par_jour";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '"'.$row["date"].'",';
                }
            }
            ?>
        ];

        var grpah1 = new Chart("graph1", {
            type: "line",
            data: {
                labels: dates,
                datasets: [{
                    label:"Récolte de Sel en fonction du temps",
                    backgroundColor: "rgb(255, 99, 132)",
                    borderColor: "rgb(255, 99, 132)",
                    fill:false,
                    data: [<?php
                        $sql = "SELECT * FROM par_jour";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '"'.$row["les"].'",';
                            }
                        }
                        ?>
                    ]
                }]
            },
            options: {}
        });

        var grpah2 = new Chart("graph2", {
        type: "line",
        data: {
            labels: dates,
            datasets: [{
                label:"Sachets de Sel en fonction du temps",
                backgroundColor: "rgb(99, 255, 132)",
                borderColor: "rgb(99, 255, 132)",
                fill:false,
                data: [<?php
                    $sql = "SELECT * FROM par_jour";
                    $result = $conn->query($sql);
                    $old_stechas = 0;

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $old_stechas = $old_stechas += $row["les"];
                            echo '"'.$old_stechas.'",';
                        }

                    }
                    ?>
                ]
            }]
        },
        options: {}
    });
    </script>
</body>
</html>
