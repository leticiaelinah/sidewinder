<?php 
    require_once ('./view/index.html');
    include_once 'config.php';
    $dbcon = new mysqli($hostname, $username, $password, $db);

    $table = '<legend align="center">Classificação</legend>
        <table align="center" class="table table-bordered">
            <tr>
                <th align="center">Posição chegada</th>
                <th align="center">Nome Piloto</th>
            </tr>';
    
    $html = '<legend align="center">Resultados</legend>
        <table align="center" class="table table-bordered">
            <tr> 
                <th align="center">Código Piloto</th> 
                <th align="center">Nome Piloto</th>
                <th align="center">Qtde Voltas Completadas</th>
                <th align="center">Melhor volta</th>
                <th align="center">Velocidade média</th>
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
            <td align="center">'. $count .'º lugar</td>
            <td align="center">'. $chegada['piloto'] .'</td>
        </tr>';
    }
    $table .= '</table>';
    
    // Melhor volta da corrida
    $select = "SELECT MIN(tempoVolta) AS tempo, nVolta, piloto FROM teste_php.corrida;";
    $result = mysqli_query($dbcon, $select);       
    $result_line = mysqli_fetch_array($result);
    $tempo = $result_line[0];
    $volta = $result_line[1];
    $piloto = $result_line[2];
    $tempo_slpit = explode(":", $tempo);
    

    // Duração da corrida
    $select_time = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tempoVolta))) AS tempo_total FROM teste_php.corrida;";
    $result_time = mysqli_query($dbcon, $select_time);       
    $time_row = mysqli_fetch_array($result_time);
    $time_split = explode(":", $time_row[0]);

    // Indicadores pilotos
    $query_select = "
        SELECT  
            codPiloto, 
            piloto, 
            COUNT(nVolta) AS qtd_voltas,
            CONCAT(min(tempoVolta), ' - ', nVolta, 'ª volta') AS melhor_volta,
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
                <td align="center">'. number_format($row['velocidade_media_piloto'], 2, '.', '') .' km/h</td>
            </tr>';
    }

    $table .= '
        <tr>
            <td align="center"></td>
            <td align="center"></td>
        </tr>
    </table>';

    $html .= '</table>';

    $html .= '<br><br>';  
    
    $html1 = '
    <fieldset>
        <legend class="lg">Melhor volta</legend>
        <i class="lg ft fas fa-stopwatch"></i> '. $tempo_slpit[0] .' minuto(s) e '. $tempo_slpit[1] .' segundo(s)
        <i class="lg ft fas fa-male"></i> '. $piloto .'
        <i class="lg ft fas fa-flag-checkered"></i> '. $volta .'ª volta
    </fieldset>
    ';
    
    $html2 = '
    <fieldset>
        <legend class="lg">Duração da prova</legend>
        <i class="lg ft far fa-clock"></i> '. $time_split[0] .' minuto(s) e '. $time_split[1] .' segundo(s)
    </fieldset>
    '; 

    echo $html1;
    echo "<br>";
    echo $html2;
    echo "<br>";
    echo $table;
    echo "<br>";
    echo $html;

    mysqli_close($dbcon);
?>