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
        echo $row[0]; # por mil, porque el highchart pide milisegundos
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
        echo $row[0]; # por mil, porque el highchart pide milisegundos
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
        echo $row[0]; # por mil, porque el highchart pide milisegundos
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
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container"></div>





<script type="text/javascript">


const chart = Highcharts.chart('container', {
  title: {
    text: 'Monthly Average Temperature',
    x: -20 //center
  },
  subtitle: {
    text: 'Source: WorldClimate.com',
    x: -20
  },
  xAxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
    ]
  },
  yAxis: {
    title: {
      text: 'Temperature (°C)'
    },
    plotLines: [{
      value: 0,
      width: 1,
      color: '#808080'
    }]
  },
  tooltip: {
    valueSuffix: '°C'
  },
  legend: {
    layout: 'vertical',
    align: 'right',
    verticalAlign: 'middle',
    borderWidth: 0,
    showInLegend: false
  },
  series: [{
    marker: {
      fillColor: 'transparent',
      lineColor: Highcharts.getOptions().colors[0]
    },
    data: [<?php datax_diaria($serie, $interval, $month, $day); ?>]
  }, {
    marker: {
      fillColor: 'transparent'
    },
    data: [<?php datay_diaria($serie, $interval, $month, $day); ?>]
  }, {

    data: [...Array(12)]
  }]
})

// Update series
setInterval(() => {
	const len = chart.series[1].data.length
  chart.series[0].update({
    marker: {
      fillColor: 'transparent',
      lineColor: Highcharts.getOptions().colors[0]
    },
    data: [...Array(len)].map(Math.random)
  })
}, 1000)

// Add point
setInterval(() => {
  chart.series[1].addPoint(Math.random())
}, 1000)

</script>