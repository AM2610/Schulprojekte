<?php
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
        margin: 0;
        padding: 0;
        height: 100vh; 
        flex-direction: column;  
        font-family: Arial, sans-serif;
        color: red; 
        box-sizing: border-box;
        }

        .header {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 20px;
        box-sizing: border-box;


        /* Text Design */
        font-family: "Roboto", sans-serif;
        font-weight: 100;
        font-style: normal;
        color: red;

        }

        h1{
        margin: 0;
        padding: 10px 20px; 
        border: 2px solid red;
        border-radius: 15px; 
        }

        .tabs {
        display: flex;
        margin-left: auto;
        gap: 20px;
        flex-shrink: 0;
        }

        .tab {
        border: 2px solid red;
        border-radius: 15px;
        padding: 10px;
        cursor: pointer;
        text-align: center;
        white-space: nowrap;
        text-decoration: none;
        color: red;
        }

        .linie {
        height: 2px;
        width: 100%;
        background-color: red;
        margin-top: 10px;
        padding-bottom: 10px;
        }

        .content{
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        }

        .frage {
        font-size: 1.2em;
        padding-top: 20px;
        }

        table{
        margin-top: 10px;
        width: 60%;
        border-collapse: collapse;
        text-align: center;
        margin-bottom: 20px;
        }

        table, th, td {
        border: 1px solid red;
        }

        th, td {
        padding: 10px;
        }


        .durchschnitt {
        display: flex;
        align-items: center;
        margin-top: 20px;
        margin-bottom: 10px;
        }
        .durchschnitt span {
        margin-right: 10px;
        font-size: 1.2em;
        }

        .box{
        width: 65px;
        height: 40px;
        border: 2px solid red;
        display: flex;
        justify-content: center;
        align-items: center;
        }

        .trennlinie{
        height: 2px;
        width: 60%;
        background-color: red;
        
        }
    </style>
    <title>Auswertung</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
<body>
    
<div class="header">
      <h1>Dashboard</h1>
      <div class="tabs">
            <a class="tab" href="index.php?order=umfrage" >Umfrage</a>
            <a class="tab" href="index.php?order=dozent">Dozent</a>
            <a class="tab" href="index.php?order=klasse">Klasse</a>
            <a class="tab" href="index.php?order=logout">Logout</a>
      </div>

    
</div>  

    <div class="content">

     <div class="linie"></div>
    
        <?php     
        

        $sql = $db->query("SELECT * FROM frage WHERE antwort_art = 0");
        
        $datensatz = $sql->fetch_all();
        // print_r($datensatz);
        $chartIndex = 0;
        foreach ($datensatz as $index) {
            $sql2 = $db->query("SELECT antwort FROM antwort WHERE frage_id = ".$index[0]);
            $datensatz2 = $sql2->fetch_all();
            $anzahl = count($datensatz2);
            $anzahl0 = 0;
            $anzahl1 = 0;
            $anzahl2 = 0;
            $anzahl3 = 0;
            $anzahl4 = 0;
            $anzahl5 = 0;
            
            
            foreach ($datensatz2 as $index2)
            {
                if($index2[0] == 0)
                    $anzahl0++;
                if($index2[0] == 1)
                    $anzahl1++;
                if($index2[0] == 2)
                    $anzahl2++;
                if($index2[0] == 3)
                    $anzahl3++;
                if($index2[0] == 4)
                    $anzahl4++;
                if($index2[0] == 5)
                    $anzahl5++;
                
            }
            $average = $anzahl == 0 ? 0 : round(($anzahl1 * 1 + $anzahl2 * 2 + $anzahl3 * 3 + $anzahl4 * 4 + $anzahl5 * 5) / ($anzahl-$anzahl0), 1);
            $syntax = getEvaluationSyntax($average);
            echo '<div class="frage">' . $index[1] . '</div>';
            echo '<table>
                    <tr>
                        <td>++</td>
                        <td>+</td>
                        <td>0</td>
                        <td>-</td>
                        <td>--</td>
                        <td>k.A.</td>
                        <td>Diagram</td>
                    </tr>
                    <tr>
                        <td>'.$anzahl5.'</td>
                        <td>'.$anzahl4.'</td>
                        <td>'.$anzahl3.'</td>
                        <td>'.$anzahl2.'</td>
                        <td>'.$anzahl1.'</td>
                        <td>'.$anzahl0.'</td>
                        <td style="display: flex; align-items: center; justify-content: center; border:0px;">
                            <div style="width:150px; height:150px;">
                                <canvas id="chart'.$chartIndex.'"></canvas>
                            </div>
                        </td>
                    </tr>
                    
                  </table>';
            echo '<div class="durchschnitt">
                    <span>Durchschnittlicher Wert:</span>
                    <div class="box" id="averageBox">' . $syntax . ' ('.$average.')</div>
                    <p>&nbsp;von ' . $anzahl-$anzahl0 .' Antworten ('.$anzahl0.' Umfrage/n wurden mit k.A. ausgefüllt)</p>
                  </div>';
            echo '<div class="trennlinie"></div>';

            echo '<script>
                var ctx = document.getElementById("chart'.$chartIndex.'").getContext("2d");
                var chart = new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: ["++", "+", "0", "-", "--", "k.A."],
                        datasets: [{
                            label: "Antworten",
                            data: ['.$anzahl5.', '.$anzahl4.', '.$anzahl3.', '.$anzahl2.', '.$anzahl1.', '.$anzahl0.'],
                            backgroundColor: [
                                "rgba(142,202,230,0.500)",
                                "rgba(165,190,0,0.500)",
                                "rgba(179,136,235,0.500)",
                                "rgba(255,183,3,0.500)",
                                "rgba(251,133,0,0.500)",
                                "rgba(239,35,60,0.500)"
                            ],
                            borderColor: [
                                "rgba(142,202,230,1.000)",
                                "rgba(165,190,0,1.000)",
                                "rgba(179,136,235,1.000)",
                                "rgba(255,183,3,1.000)",
                                "rgba(251,133,0,1.000)",
                                "rgba(239,35,60,1.000)"
                            ],
                            borderWidth: 1
                        }]
                    },
                    showDatapoints: true,
                    options: {
                        radius: "100%",
                        plugins: {
                            tooltips: {
                                enabled: false
                            },
                            pieceLabel: {
                                mode: "value"
                            },
                            responsive: true,
                            legend: {
                                position: "right",
                            },
                            animation: {
                                animateScale: true,
                                animateRotate: true
                            }
                        }
                    }
                });
              </script>';

            $chartIndex++;
        }
        
        ?>
    </div>

</body>

</html>