<?php
$dsn = "mysql:dbname=mpog_lmeo;host=127.0.0.1";
$dbuser = "root";
$dbpass = "";

try{
    $pdo = new PDO($dsn,$dbuser,$dbpass);
    //echo("Conexão estabelecida com sucesso");
} catch(PDO_EXCEPTION $e){
    echo "Falhou: ".$e->getMessage();
}

try{
    $sql = "SELECT Latitude, Longitude, estacao.Nome as EstacaoNome, bacia.Nome as BaciaNome from estacao
            INNER JOIN bacia ON estacao.BaciaCodigo = bacia.RegistroID ";
    $sql = $pdo->query($sql);
}catch(PDO_EXCEPTION $e){
    echo "Falhou seleção: ".$e->getMessage();
}

$array_json = [];
foreach ($sql as $key => $row) {
    $array_json[$key]['roi_point'] = $row['Latitude'] ." ". $row['Longitude'];
    $array_json[$key]['roi_name']  = utf8_encode($row['EstacaoNome']);
    $array_json[$key]['roi_description']  = utf8_encode($row['BaciaNome']);
};

//print_r($array_json);                                            
$json = json_encode($array_json);

print_r($json);

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