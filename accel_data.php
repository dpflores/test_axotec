<?php

# Server and table connection
$servername = "localhost";
$username = "root";
$password = "";

$connection = new mysqli($servername, $username, $password);  # base de datos en localhost, de ahi credenciales
mysqli_select_db($connection,"prueba");
mysqli_query($connection, "SET NAMES 'utf8'");

$serie = "111";
$month= "12";
$day = "02";

$interval = 0;

function datax_diaria($serie, $interval, $month, $day) {
    // echo "hello";
    global $connection;
    $year = date("Y");
    #obtenemos el resultado en unix time y las 3 aceleraciones de acuerdo a los filtros de fecha y serie
    // $request = "SELECT UNIX_TIMESTAMP(fecha), accel_y FROM datos WHERE year(`fecha`) = '$year' AND month(`fecha`) = '$month' AND day(`fecha`) = '$day' AND `serie` = '$serie';";
    $request = "SELECT UNIX_TIMESTAMP(fecha), accel_x FROM datos WHERE year(`fecha`) = '$year' AND `serie` = '$serie';";    
    $result = mysqli_query($connection,$request);

    # Iterar en los resultados
    while ($row=mysqli_fetch_array($result)) {
        echo "[";
        echo $row[0]*1000; # por mil, porque el highchart pide milisegundos
        echo ",";
        echo $row[1];       #accelx
        echo "],";
        for ($x=0;$x<$interval;$x++){
            $row = mysqli_fetch_array($result);
        }
    }

}



function datay_diaria($serie, $interval, $month, $day) {
    // echo "hello";
    global $connection;
    $year = date("Y");
    #obtenemos el resultado en unix time y las 3 aceleraciones de acuerdo a los filtros de fecha y serie
    // $request = "SELECT UNIX_TIMESTAMP(fecha), accel_y FROM datos WHERE year(`fecha`) = '$year' AND month(`fecha`) = '$month' AND day(`fecha`) = '$day' AND `serie` = '$serie';";
    $request = "SELECT UNIX_TIMESTAMP(fecha), accel_y FROM datos WHERE year(`fecha`) = '$year' AND `serie` = '$serie';";    
    $result = mysqli_query($connection,$request);

    # Iterar en los resultados
    while ($row=mysqli_fetch_array($result)) {
        echo "[";
        echo $row[0]*1000; # por mil, porque el highchart pide milisegundos
        echo ",";
        echo $row[1];       #accelx
        echo "],";
        for ($x=0;$x<$interval;$x++){
            $row = mysqli_fetch_array($result);
        }
    }

}

function dataz_diaria($serie, $interval, $month, $day) {
    // echo "hello";
    global $connection;
    $year = date("Y");
    #obtenemos el resultado en unix time y las 3 aceleraciones de acuerdo a los filtros de fecha y serie
    // $request = "SELECT UNIX_TIMESTAMP(fecha), accel_y FROM datos WHERE year(`fecha`) = '$year' AND month(`fecha`) = '$month' AND day(`fecha`) = '$day' AND `serie` = '$serie';";
    $request = "SELECT UNIX_TIMESTAMP(fecha), accel_z FROM datos WHERE year(`fecha`) = '$year' AND `serie` = '$serie';";    
    $result = mysqli_query($connection,$request);

    # Iterar en los resultados
    while ($row=mysqli_fetch_array($result)) {
        echo "[";
        echo $row[0]*1000; # por mil, porque el highchart pide milisegundos
        echo ",";
        echo $row[1];       #accelx
        echo "],";
        for ($x=0;$x<$interval;$x++){
            $row = mysqli_fetch_array($result);
        }
    }

}


?>


<!-- ajax -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<!-- highchart -->
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<div id="container"></div>





<script type="text/javascript">



// Create the chart
Highcharts.stockChart('container', {
    chart: {
        events: {
            load: function () {

                // set up the updating of the chart each second
                var series1 = this.series[0];
                var series2 = this.series[1];
                var series3 = this.series[2];
                setInterval(function () {
                    
                    var JSON=$.ajax({
                    url:"http://localhost/test_axotec/datos.php?q=1",
                    dataType: 'json',
                    async: false}).responseText;
                    var Respuesta=jQuery.parseJSON(JSON);

                    var t = (new Date()).getTime();
                    
                    var acc_x = Respuesta[0].accel_x;
                    var acc_y = Respuesta[0].accel_y;
                    var acc_z = Respuesta[0].accel_z;
            
    
                    series1.addPoint([t,acc_x], true, true);
                    series2.addPoint([t,acc_y], true, true);
                    series3.addPoint([t,acc_z], true, true);
                }, 1000);
            }
        }
    },

    accessibility: {
        enabled: false
    },

    time: {
        useUTC: false
    },

    rangeSelector: {
        buttons: [{
            count: 1,
            type: 'minute',
            text: '1M'
        }, {
            count: 5,
            type: 'minute',
            text: '5M'
        }, {
            type: 'all',
            text: 'All'
        }],
        inputEnabled: false,
        selected: 0
    },

    title: {
        text: 'Datos de aceleración del Axotec'
    },

    exporting: {
        enabled: false
    },
    

    series: [{
        name: 'Aceleración x',
        data: [<?php datax_diaria($serie, $interval, $month, $day); ?>],
        },
        {
        name: 'Aceleración y',
        data: [<?php datay_diaria($serie, $interval, $month, $day); ?>],
        },
        {
        name: 'Aceleración z',
        data: [<?php dataz_diaria($serie, $interval, $month, $day); ?>],
        },
    ]
});


</script>