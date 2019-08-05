<?php 
    include_once 'config.php';
    $dbcon = new mysqli($hostname, $username, $password, $db);

    $table = '
        <table>
            <tr>
                <th>Posição chegada</th>
                <th>Nome Piloto</th>
            </tr>';
    
    $html = '
        <table>
            <tr> 
                <th>Código Piloto</th> 
                <th>Nome Piloto</th>
                <th>Qtde Voltas Completadas</th>
                <th>Melhor volta</th>
                <th>Velocidade média</th>
            </tr>';
    
    // Ranking
    $select_posicao = "
    (SELECT DISTINCT piloto, 
        nVolta, 
        tempoVolta,
        IF(nVolta = 3, 6, @rownum := @rowmun + 1) AS posicao
    FROM 
        teste_php.corrida, (SELECT @rowmun := 0) r
    WHERE 
        nVolta = 3 
        AND codpiloto NOT IN (SELECT DISTINCT codpiloto
                          FROM teste_php.corrida
                          WHERE nVolta = 4)
    ORDER BY tempoVolta ASC)
    UNION
    (SELECT DISTINCT piloto, 
        nVolta, 
        tempoVolta,
        IF(nVolta = 3, 6, @rownum := @rowmun + 1) AS posicao
    FROM 
        teste_php.corrida, (SELECT @rowmun := 0) r
    WHERE nVolta = 4
    ORDER BY tempoVolta ASC) ORDER BY posicao, tempoVolta ASC;";
    $result_posicao = mysqli_query($dbcon, $select_posicao);
    $ranking = array();
    $count = 0;

	while ($chegada = mysqli_fetch_array($result_posicao)) 
        $ranking[] = $chegada;
	    
	foreach ($ranking as $chegada) {
        $count += 1;
        $table .= '
        <tr>
            <td align="center">'. $count .'</td>
            <td align="center">'. $chegada['piloto'] .'</td>
        </tr>';
    }
    $table .= '</table>';
    
    // Melhor volta da corrida
    $select = "SELECT MIN(tempoVolta) AS tempo, nVolta, piloto FROM teste_php.corrida;";
    $result = mysqli_query($dbcon, $select);       
    $result_line = mysqli_fetch_array($result);

    // Duração da corrida
    $select_time = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tempoVolta))) AS tempo_total FROM teste_php.corrida;";
    $result_time = mysqli_query($dbcon, $select_time);       
    $time_row = mysqli_fetch_array($result_time);

    // Indicadores pilotos
    $query_select = "
        SELECT  
            codPiloto, 
            piloto, 
            COUNT(nVolta) AS qtd_voltas,
            CONCAT(min(tempoVolta), ' - ', nVolta) AS melhor_volta,
            (SUM(VelocidadeMediaDaVolta) / COUNT(nVolta)) AS velocidade_media_piloto
        FROM 
            teste_php.corrida
        GROUP BY 
            codPiloto, piloto
        HAVING COUNT(nVolta) > 1;";
    $result_select = mysqli_query($dbcon, $query_select);

    $rows = array();

	while ($row = mysqli_fetch_array($result_select)) 
        $rows[] = $row;
	    
	foreach ($rows as $row) { 

        $html .= '
            <tr>
                <td align="center">'. $row['codPiloto']  .'</td>
                <td align="center">'. stripcslashes($row['piloto'])  .'</td>
                <td align="center">'. $row['qtd_voltas']  .'</td>
                <td align="center">'. $row['melhor_volta'] .'</td>
                <td align="center">'. number_format($row['velocidade_media_piloto'], 2, '.', '') .'</td>
            </tr>';
    }

    $table .= '
        <tr>
            <td align="center"></td>
            <td align="center"></td>
        </tr>
    </table>';

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

    $html .= '<br><br>';  
    
    $html .= '<h3>Melhor volta da corrida: '. $result_line[2] .' - Volta '. $result_line[1] .' - Tempo '. $result_line[0] .'</h3>'; 
    $html .= '<br>'; 
    $html .= '<h3>Tempo Total de Prova: '. $time_row[0] .' minutos</h3>';

    echo $table;
    echo "<br>";
    echo $html;

    mysqli_close($dbcon);
?>