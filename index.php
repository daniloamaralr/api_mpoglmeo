<?php

$data = array("data" => "1975-02-01", "lat" => "-20.1847", "long" => "-44.8933");                                                                    
$data_string = json_encode($data);

$cURL = curl_init(''); // url da api
curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
// Definimos um array seguindo o padrão:
//  '<name do input>' => '<valor inserido>'

// Iremos usar o método POST
curl_setopt($cURL, CURLOPT_POST, true);
// Definimos quais informações serão enviadas pelo POST (array)
curl_setopt($cURL, CURLOPT_POSTFIELDS, $data_string);


curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                        

$resultado = curl_exec($cURL);
echo "  " .$resultado;

$resultado = true;
if ($resultado) {   // resultado ok - retorno de sucesso da api
    $dsn = "mysql:dbname=mpog_lmeo;host=127.0.0.1";
    $dbuser = "root";
    $dbpass = "";

    try{
        $pdo = new PDO($dsn,$dbuser,$dbpass);
        //echo("Conexão estabelecida com sucesso");
    } catch(PDO_EXCEPTION $e){
        echo "Falhou: ".$e->getMessage();
    }

    $json = json_decode($data_string, true);
    //echo '<pre>' . print_r($json, true) . '</pre>';
    //print_r($json['data']);
    //exit;

    
    try{
            $data  = date("Y-m-d", strtotime($json['data']));
            $lat = $json['lat'];
            $long = $json['long'];
    
            $sql = "INSERT INTO log_api VALUES ('', $long, $lat,'$data')" ;
            $sql = $pdo->query($sql);
    }catch(PDO_EXCEPTION $e){
        echo "Falhou inserção: ".$e->getMessage();
    }

}



//curl_close($cURL);
?>