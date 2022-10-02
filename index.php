<?php

$objectif = 1000;

$objectif2 = 2500;

$objectif3 = 5000;

session_start();

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);



$servername = "sql111.byethost32.com";

$username = "b32_25234654";

$password = "";

$dbname = "b32_25234654_les_fles";



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

?>

<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les Fles &bull; MAJ du <?= $last_date ?></title>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>



    <?php

    if($objectif-(round($total_stehcas*0.8)) <= 150 && $objectif-(round($total_stehcas*0.8)) > 0){

        ?><div style="display: block; background: red; color: white; padding: 14px;"><b>Rush final !</b> C'est l'heure du rush final, donnez tout ce que vous avez ;)</div><?php

    }

    ?>

    

    <div class="header">

        <?php

        if(!isset($_SESSION["user"])){

            echo '<a href="discord.php" style="text-decoration: none; color: white;">Connexion avec Discord | Connectez-vous avec Discord pour voir les prévisions</a>';

        } else {

            echo '<a style="font-weight: 600">Bonjour '.$_SESSION["user"]->username.'#'.$_SESSION["user"]->discriminator.'</a>';

            if($_SESSION["user"]->id == "229178398893801472") {

                echo '<a href="https://discord.gg/kTEb8ScskK" style="text-decoration: none; color: white;"> | Tchat</a>';

            }

        }

        ?>

    </div>



    <div class="page">

        <div class="container">

            <div class="elementb">

                <span class="title">5 Sept.</span>

                <span class="text">Démarrage</span>

            </div>

            <div class="elementb">

                <span class="title"><?= $objectif-(round($total_stehcas*0.8)) ?></span>

                <span class="text">Nombre de sachets à récolter avant réussite</span>

            </div>

        </div>



        <div class="container">

            <div class="element">

                <span class="title"><?= $total_stehcas ?></span>

                <span class="text">Nombre de stehcas</span>

            </div>

            <div class="element">

                <span class="title"><?= $total_stehcas*0.8 ?></span>

                <span class="text">Poids des stehcas en g</span>

            </div>

            <div class="element">

                <span class="title"><?= round(($total_stehcas*0.8)/($objectif/100), 2) ?></span>

                <span class="text">% d'accomplissement</span>

            </div>

        </div>



        <div class="container">

            <div class="element">

                <span class="title"><?= $objectif ?></span>

                <span class="text">Objectif en g</span>

            </div>

            <div class="element">

                <span class="title"><?= round($stehcas_quot) ?></span>

                <span class="text">Moyenne de stehcas quotienne</span>

            </div>

            <div class="element">

                <span class="title"><?= $objectif2 ?></span>

                <span class="text">Prochain objectif en g</span>

            </div>

        </div>



        <div class="container">

            <div class="elementb">

                <span class="title"><?= round(($total_stehcas*0.8)/5, 0) ?></span>

                <span class="text">personnes alimentables</span>

            </div>

            <div class="elementb">

                <span class="title"><?= round(($total_stehcas*0.8)/38, 0) ?></span>

                <span class="text">Litre d'eau de mer nécéssaires pour avoir autant de les</span>

            </div>

        </div>



        <div class="chart">

            <canvas id="myChart1"></canvas>

            <canvas id="myChart2"></canvas>

        </div>



        <br>



        <h1>Paliers d'objectif</h1>

        <?= $objectif ?> stehcas (Soirée League of Legends): <?php if($total_stehcas*0.8 >= $objectif) {echo "<span class='yes obj-list'>Atteint</span>";}else{echo "<span class='no obj-list'>Non atteint</span>";}?><br>

        <?= $objectif2 ?> stehcas (Soirée chépakoi [n'importe nawak]): <?php if($total_stehcas*0.8 >= $objectif2) {echo "<span class='yes obj-list'>Atteint</span>";}else{echo "<span class='no obj-list'>Non atteint</span>";}?><br>

        <?= $objectif3 ?> stehcas (Bridage du sel): <?php if($total_stehcas*0.8 >= $objectif3) {echo "<span class='yes obj-list'>Atteint</span>";}else{echo "<span class='no obj-list'>Non atteint</span>";}?>



        <h1>Prévisions & Moyennes par jour</h1>

        <?php

        if(isset($_SESSION["user"])){
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

            <div class="container">

                <div class="element">

                    <span class="title"><?= round($moy_lun)?> </span>

                    <span class="text">Moyenne le lundi</span>

                </div>

                <div class="element">

                    <span class="title"><?= round($moy_mar)?> </span>

                    <span class="text">Moyenne le mardi</span>

                </div>

                <div class="element">

                    <span class="title"><?= round($moy_mer)?> </span>

                    <span class="text">Moyenne le mercredi</span>

                </div>

            </div>



            <div class="container">

                <div class="elementb">

                    <span class="title"><?= round($moy_jeu)?> </span>

                    <span class="text">Moyenne le jeudi</span>

                </div>

                <div class="elementb">

                    <span class="title"><?= round($moy_ven)?> </span>

                    <span class="text">Moyenne le vendredi</span>

                </div>

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

            echo '<br><br><br><br><br><br><br><br><br><h2>

            Fin de l\'objectif estimé: '.gmdate('j M', $time_collect).'. '.gmdate('Y', $time_collect).' avec '.round($prev_actual).' stehcas !</h2>';

            ?>

                Nombre de lundis restant(s): <?= $prev_lundi ?><br>

                Nombre de mardis restant(s): <?= $prev_mardi ?><br>

                Nombre de mercredis restant(s): <?= $prev_mercredi ?><br>

                Nombre de jeudis restant(s): <?= $prev_jeudi ?><br>

                Nombre de vendredis restant(s): <?= $prev_vendredi ?><br>

                Nombre de week-end restant(s): <?= $prev_we ?>

            <?php

        } else {

            echo "<p>Vous devez être connecté avec Discord pour voir les statistiques détaillées !</p>";

        }

        ?>



        

        

    </div>

    <br><br><br><br><br><br>



    <script>

        const labels = [

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



        const data1 = {

            labels: labels,

            datasets: [{

                label: 'Stehcas de les',

                backgroundColor: 'rgb(255, 99, 132)',

                borderColor: 'rgb(255, 99, 132)',

                data: [<?php

                $sql = "SELECT * FROM par_jour";

                $result = $conn->query($sql);



                if ($result->num_rows > 0) {

                    while($row = $result->fetch_assoc()) {

                        echo '"'.$row["les"].'",';

                    }

                }

                ?>],

            }

        ]

        };



        const data2 = {

            labels: labels,

            datasets: [{

                label: 'Evolution du nombre de Stehcas',

                backgroundColor: 'rgb(131, 232, 0)',

                borderColor: 'rgb(131, 232, 0)',

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

                ?>],

            }

        ]

        };



        let delayed1;

        const config1 = {

            type: 'line',

            data: data1,

            options: {

                animation: {

                    onComplete: () => {

                        delayed1 = true;

                    },

                    delay: (context) => {

                        let delay = 0;

                        if (context.type === 'data' && context.mode === 'default' && !delayed1) {

                        delay = context.dataIndex * 300 + context.datasetIndex * 100;

                        }

                        return delay;

                    },

                },

                scales: {

                x: {

                    stacked: true,

                },

                y: {

                    stacked: true

                }

                }

            }

        };



        let delayed2;

        const config2 = {

            type: 'line',

            data: data2,

            options: {

                animation: {

                    onComplete: () => {

                        delayed2 = true;

                    },

                    delay: (context) => {

                        let delay = 0;

                        if (context.type === 'data' && context.mode === 'default' && !delayed2) {

                        delay = context.dataIndex * 300 + context.datasetIndex * 100;

                        }

                        return delay;

                    },

                },

                scales: {

                x: {

                    stacked: true,

                },

                y: {

                    stacked: true

                }

                }

            }

        };



        const myChart1 = new Chart(

            document.getElementById('myChart1'),

            config1

        );



        const myChart2 = new Chart(

            document.getElementById('myChart2'),

            config2

        );

    </script>

 

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        .obj-list {
            font-weight: 500;
        }

        .yes {
            color: green;
        }

        .no {
            color: red;
        }

        .chart {
            width: 70vw;
            margin-left: auto;
            margin-right: auto;
        }

        .header {
            padding: 14px;
            display: block;
            background: #0099CC;
        }

        body {
            margin:auto 0;
            padding: 0px;
            background: #0099FF;
            color: white;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
        }

        .container {
            display: block;
            margin-top: 10vh;
            width: 98vw;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .element {
            width: 31vw;
            float: left;
            text-align: center;
            display: block;
        }

        .elementb {
            width: 47vw;
            float: left;
            text-align: center;
            display: block;
        }

        .title, .text {
            display: block;
        }

        .title {
            font-size: 250%;
            font-weight: 800;
        }


        @media only screen and (max-width: 600px) {
            body {
                font-size: 100%;
            }

            .chart {
                display: none;
            }
        }
        

    </style>



</body>

</html>