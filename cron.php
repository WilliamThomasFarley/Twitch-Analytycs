<?php
/*
This is a main file which will send requests to Twitch's API, get wievers amount and store that data in DB
*/
$time = time(); 

//Connestion data
$host = "localhost";  
$user = "root";  
$pass = "";   
$db   = "twitch-analytycs";     
$connect = mysql_connect($host, $user , $pass) or die("Ошибка соединения с сервером");
    mysql_select_db( $db, $connect );
    if($connect) {
        echo "<title>It works !</title>";
    };
//Get data from Twitch API
    $gamesData = json_decode(file_get_contents('https://api.twitch.tv/kraken/games/top?limit=100'), TRUE);
        foreach ( $gamesData[top] as $value ) {
//echo $value[game][name] . '  ' . $value[viewers] . "<br>";
            $gameName = mysql_real_escape_string($value[game][name]);
            $gameVwrs = $value[viewers];
              $exists = mysql_query("SELECT `gameName` FROM `control` WHERE `gameName` = '$gameName' ");
              if(mysql_num_rows($exists)>0){   echo 'Запись ' . $gameName . ' уже есть в БД<br>';
                }else{
                    mysql_query("INSERT INTO control (`gameName`) VALUES ('$gameName') ON DUPLICATE KEY UPDATE gameName='$gameName' ") or die(mysql_error());
                        echo 'Добавлена в БД запись : ' . $gameName . ' <br>';}
              
              
/* Part 2 */
    //$gameName = mysql_real_escape_string($value[game][name]);
    mysql_query("INSERT INTO data (`gameName`,`vwrsAmount`,`timestamp`) VALUES ('$gameName','$gameVwrs','$time') ") or die(mysql_error());
    
    //echo $gameName . ' has ' . $gameVwrs . ' and time is: ' . $time .'<br>'; 	
        
/* Array structure sheet
echo "<pre>";
print_r($gamesData[top]);
echo "</pre>";
*/
}
?>                                            

                                         