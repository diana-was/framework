<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
            table, td, th
            {
                border:1px solid blue;
            }
            th
            {
                background-color:blue;
                color:white;
            }
            td, th
            {
                padding: 4px;
            }
        </style>
    </head>
    <body>
        <div>
            <?php foreach ($Tools->tables as $name => $data) : ?>
            <h2><?php echo $name; ?></h2>
            <table style="border">
                <?php foreach ($data['fields'] as $n => $row) : 
                        if ($n == 0) {
                            $keys = array_keys($row);
                            echo "<tr>";
                            foreach ($keys as $th) {
                                echo "<th>$th</th>";
                            }
                            echo "</tr>";
                        }
                        echo "<tr>";
                        foreach ($row as $k => $td) {
                            if ($k == 'tsi' || $k == 'tsu')
                                echo '<td>'.date('Y-m-d',strtotime($td)).'</td>';
                            else
                                echo "<td>$td</td>";
                        }
                        echo "</tr>";
                    ?>
                <?php endforeach; ?>
                <tr>
                <td>Total Rows</td>
                <td><?php echo $data['rows']; ?></td>
                </tr>
            </table>
            <?php endforeach; ?>
        </div>
    </body>
</html>
