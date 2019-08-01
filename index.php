<?php 
    // Abrir arquivo para leitura
    $f = fopen('uteis/log-corrida.csv', 'r');

    $cabecalho = fgetcsv($f, null, ';', '"');
    
    while (!feof($f)) {
        $linha = fgetcsv($f, null, ';', '"');
        if (!$linha) {
            continue;
        }
        print_r($linha);
    }

    fclose($f);

?>