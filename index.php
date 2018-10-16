<?php
/**
 * Created by PhpStorm.
 * User: Noemi
 * Date: 15/10/2018
 */


function findLongestFromACell($x, $y, $i, $j, $mat, $dp)
{
    $maxValue = 0;
    $startValue = 1501;
    $path = [];

    if (is_array($dp[$i][$j]))
    {
        return $dp[$i][$j];
    }

    if ($j < $y - 1 && ($mat[$i][$j] > $mat[$i][$j + 1]))
    {
        $aux = findLongestFromACell($x, $y, $i, $j + 1, $mat, $dp);
        $maxValue = $aux['maxValue'];
        $startValue = $aux['start'];
        $path = $aux['path'];
    }

    if ($j > 0 && ($mat[$i][$j] > $mat[$i][$j - 1]))
    {
        $aux = findLongestFromACell($x, $y, $i, $j - 1, $mat, $dp);
        if (($aux['maxValue'] > $maxValue) || ($aux['maxValue'] == $maxValue && $aux['start'] < $startValue))
        {
            $maxValue = $aux['maxValue'];
            $startValue = $aux['start'];
            $path = $aux['path'];
        }
    }

    if ($i > 0 && ($mat[$i][$j] > $mat[$i - 1][$j]))
    {
        $aux = findLongestFromACell($x, $y, $i - 1, $j, $mat, $dp);
        if (($aux['maxValue'] > $maxValue) || ($aux['maxValue'] == $maxValue && $aux['start'] < $startValue))
        {
            $maxValue = $aux['maxValue'];
            $startValue = $aux['start'];
            $path = $aux['path'];
        }

    }

    if ($i < $x - 1 && ($mat[$i][$j] > $mat[$i + 1][$j]))
    {
        $aux = findLongestFromACell($x, $y, $i + 1, $j, $mat, $dp);
        if (($aux['maxValue'] > $maxValue) || ($aux['maxValue'] == $maxValue && $aux['start'] < $startValue))
        {
            $maxValue = $aux['maxValue'];
            $startValue = $aux['start'];
            $path = $aux['path'];
        }
    }

    if ($maxValue > 0)
    {
        $dp[$i][$j] = ['maxValue' => 1 + $maxValue];
        $dp[$i][$j]['start'] = $startValue;
        $dp[$i][$j]['path'] = $path;
        array_unshift($dp[$i][$j]['path'], $mat[$i][$j]);
    } else
    {
        $dp[$i][$j] = ['maxValue' => 1];
        $dp[$i][$j]['start'] = $mat[$i][$j];
        $dp[$i][$j]['path'][] = $mat[$i][$j];
    }

    return $dp[$i][$j];
}


function finLongestOverAll($mat, $x, $y)
{
    $result = ['maxValue' => 0, 'difValue' => 0, 'path' => []];

    for ($i = 0; $i < $x; $i++)
    {
        $dp[$i] = array_fill(0, $y, -1);
    }

    for ($i = 0; $i < $x; $i++)
    {
        for ($j = 0; $j < $y; $j++)
        {
            if ($dp[$i][$j] == -1)
            {
                $dp[$i][$j] = findLongestFromACell($x, $y, $i, $j, $mat, $dp);
                if (($dp[$i][$j]['maxValue'] > $result['maxValue']) || ($dp[$i][$j]['maxValue'] == $result['maxValue'] && ($mat[$i][$j] - $dp[$i][$j]['start']) > $result['difValue']))
                {
                    $result['maxValue'] = $dp[$i][$j]['maxValue'];
                    $result['difValue'] = $mat[$i][$j] - $dp[$i][$j]['start'];
                    $result['path'] = $dp[$i][$j]['path'];
                }
            }
        }
    }
//    echo("<br/>Max Path: " . $result['maxValue'] . " - Drop: " . $result['difValue']. " and Path: ");
//    var_dump($result['path']);
    return $result;
}


$matrix = [
    [4, 8, 7, 3],
    [2, 5, 9, 3],
    [6, 3, 2, 5],
    [4, 4, 1, 6],
];

echo("<br/>");
$result = finLongestOverAll($matrix, 4, 4);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ski map's challenge</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<div class="col-md-6 p-lg-5 mx-auto my-5">
    <table class="table table-bordered">
        <tbody>
        <?php
        foreach ($matrix as $row)
        {
            echo('<tr>');
            foreach ($row as $element)
            {
                echo('<td>' . $element . '</td>');
            }
            echo('</tr>');
        }
        ?>
        </tbody>
    </table>
    <?php
    echo("<br/>Max Path: " . $result['maxValue'] . " - Drop: " . $result['difValue'] . " - Path: ");
    foreach ($result['path'] as $step){
        echo($step.", ");
    }
    ?>
</div>
</body>
</html>
