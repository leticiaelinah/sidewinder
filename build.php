<?php 
    include_once 'config.php';
    $dbcon = new mysqli($hostname, $username, $password, $db);

    $html = '
        <table>
            <tr>
                <th>Posição Chegada</th> 
                <th>Código Piloto</th> 
                <th>Nome Piloto</th>
                <th>Qtde Voltas Completadas</th>
                <th>Tempo Total de Prova</th>
            </tr>';
    
    $query_select = "
        SELECT  
            codPiloto, 
            piloto, 
            COUNT(nVolta) AS qtd_voltas 
        FROM teste_php.corrida
        GROUP BY codPiloto, piloto
        HAVING COUNT(nVolta) > 1";
    $result_select = mysqli_query($dbcon, $query_select);

    $rows = array();

	while ($row = mysqli_fetch_array($result_select)) 
        $rows[] = $row;
	    
	foreach ($rows as $row) { 
        // $hora = $row['hora'];
        // $codPiloto = $row['codPiloto'];
        // $piloto = stripcslashes($row['piloto']);
        // $nVolta = $row['nVolta'];
        // $tempoVolta = $row['tempoVolta'];
        // $VelocidadeMediaDaVolta = $row['VelocidadeMediaDaVolta'];

        $html .= '
            <tr>
                <td>–</td>
                <td>'. $row['codPiloto']  .'</td>
                <td>'. stripcslashes($row['piloto'])  .'</td>
                <td>'. $row['qtd_voltas']  .'</td>
                <td>–</td>
            </tr>';
    }

    $html .= '
        </table>
        <style>
            table {
                border-collapse: collapse;
            }
    
            table, th, td {
                border: 1px solid black;
            }
        </style>';

    echo $html;

    mysqli_close($dbcon);
?>