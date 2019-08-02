<?php 
    // Abrir arquivo csv
    $arquivo = fopen('uteis/log-corrida.csv', 'r');

    // Separar o nome das colunas para desconsiderar
    $column = fgetcsv($arquivo, null, ';', '"');
    global $qtdlinhas;

    $html = '
        <table>
            <tr>
                <th>Posição</th>
                <th>Código Piloto</th>
                <th>Nome</th>
                <th>Voltas Completas</th>
                <th>Tempo</th>
            </tr>';

    $array_count = [];

    while (!feof($arquivo)) {
        $qtdlinhas += 1;
        $row = fgetcsv($arquivo, null, ';', '"');
        $numR = count($row);

        // print_r($row[1]);

        $identificacao = explode("–", $row[1]);
        $id = $identificacao[0];
        $nome = $identificacao[1];
        // print_r($nome);

        

        if (array_key_exists(rtrim($id), $array_count)) { 
            // print_r($array_count);
            // print_r('</br>');
            $array_count[rtrim($id)] = $array_count[rtrim($id)] + 1;
        }else{
            // array_push($array_count,[rtrim($id) => 0]);
            $array_count += [rtrim($id) => 0];
        }


            $html .= '<tr>
            <td>'. $row[0]  .'</td>
            <td>'. rtrim($identificacao[0])  .'</td>
            <td>'. rtrim($identificacao[1])  .'</td>
            <td>'. $row[2]  .'</td>
            <td>'. $row[3]  .'</td>
            <td>'. $row[4]  .'</td>
            </tr>';


        // foreach ($row as $linha) {
        //     var_dump($linha);
        //     // $split = explode(",", $linha);
        //     // print_r($split);
        //     // echo $split[0] . ' <br> ';

        //     // echo $linha;

        //     $html .= '<tr>
        //         <td>1</td>
        //         <td>1</td>
        //         <td>1</td>
        //         <td>1</td>
        //         <td>1</td>
        //     </tr>';
        // }
 
        // $html .= '
        //     </table>
        //     <style>
        //     table {
        //         border-collapse: collapse;
        //     }
            
        //     table, th, td {
        //         border: 1px solid black;
        //     }
        //     </style>';
    }
    print_r($array_count);exit;

    fclose($arquivo);
    print_r($html);exit;

    // echo $html;
    
?>