<?php
    $link = 'http://api.openweathermap.org/data/2.5/weather';
    $appid = 'f42de21950038b0845d53fcb21e8df6a';    
    $file = JSON_decode(file_get_contents("city.list.json"), true);
    $city = $_POST['city'];             
    
    if (empty($_POST['city'])) {
        $url = "$link?id=524894&units=metric&appid=$appid";
        $cityAppload = JSON_decode(file_get_contents($url), true, 4);  
    } else {
        $url = "$link?id=$city&units=metric&appid=$appid";
        $cityAppload = JSON_decode(file_get_contents($url), true, 4);
    }   

    function compare($a, $b) {
        return ($a['name'] < $b['name']) ? -1 : 1;
    } 

    usort($file, "compare");

    $temp = round($cityAppload['main']['temp'], 1, PHP_ROUND_HALF_UP);
    $date = date('H:i, d M Y', $cityAppload['dt']);
    $latitude = $cityAppload['coord']['lat'];
    $longitude = $cityAppload['coord']['lon'];        
    $pressure = $cityAppload['main']['pressure'];
    $humidity = $cityAppload['main']['humidity'];

    $windSpeed = $cityAppload['wind']['speed'];
    $windDirect = round($cityAppload['wind']['deg']);        

    $sunrise = date('H:i', $cityAppload['sys']['sunrise']);
    $sunset = date('H:i', $cityAppload['sys']['sunset']);

    $imgName = $cityAppload['weather'][0]['icon'];
    $imgURL = 'http://openweathermap.org/img/w/'.$imgName.'.png';    
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Weather</title>
  <link rel="stylesheet" href="css/styles.css">  
</head>
<body>    

    <form action="" method="POST" name="weather" >
        <select name = 'city'>
            <option>--Выберите город--</option>

            <? foreach ($file as $city) {
                if ($city['country'] === "RU" && $city['name'] !== '-' && $city['name'] !== '') {?>
                    <option value="<?= $city['id'] ?>"><?= $city['name'] ?> </option>
                <?}
            }?>
        </select><br>

        <input type="submit" value="Посмотреть погоду">            
    </form>     

    <div class="weather-conditions">
        
        <h3>Погода в <span><?= $cityAppload['name'] ?></span></h3>
        <div class="temp-block">
            <img src=" <?= $imgURL ?> " alt="" width="50" height="50">
            <div class="temp"><span><?= $temp ?> &degC</span></div> 
        </div>
        <div class="date"><?= $date ?></div> 

        <table>
            <tr>
                <td><img src="img/coord.png" alt="" width="25" height="25"></td>
                <td class="coords">
                    <span>Широта: <?= $latitude ?></span><br/>
                    <span>Долгота: <?= $longitude ?></span>
                </td>
            </tr>

            <tr>
                <td><img src="img/pressure.png" alt="" width="25" height="25"></td>
                <td class="pressure"><?= $pressure ?> hpa</td>
            </tr>

            <tr>
                <td><img src="img/humid.png" alt="" width="25" height="25"></td>
                <td class="humid"><?= $humidity ?>%</td>
            </tr>

            <tr>
                <td><img src="img/wind.png" alt="" width="25" height="25"></td>
                <td class="wind">
                    <span>Скорость: <?= $windSpeed ?> m/s</span><br/>
                    <span>Направление: <?= $windDirect ?></span>
                </td>
            </tr>

            <tr>
                <td><img src="img/sunset.png" alt="" width="25" height="25"></td>
                <td class="sun_status">
                    <span>Восход: <?= $sunrise ?></span><br/>
                    <span>Закат: <?= $sunset ?></span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>