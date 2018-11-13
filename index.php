<?php
/**
 * Created by PhpStorm.
 * User: Noemi
 * Date: 15/10/2018
 */

ini_set('max_execution_time', 300);
ini_set('memory_limit', '-1');

/**
 * @param $x - Dimesion x or number of lines of the map's matrix
 * @param $y - Dimesion y or number of columns of the map's matrix
 * @param $i - Line or x value of the position we are going to compute
 * @param $j - Column or y value of the position we are going to compute
 * @param &$mat - reference to the Map's matrix
 * @param &$dp - reference to the Caché matrix to store the results
 * @return mixed - Return the result of compute a determinate position
 * Function that compute the possibilities for a position
 */
function findLongestFromACell($x, $y, $i, $j, &$mat, &$dp)
{
    $maxValue = 0;
    $startValue = 1501;
    $path = [];

    // If this cell has already been computed return the result
    if (is_array($dp[$i][$j]))
        return;

    // If we can go west, compute the next cell in the way
    if ($j < $y - 1 && ($mat[$i][$j] > $mat[$i][$j + 1]))
    {
        // We compute the next position before this one
        findLongestFromACell($x, $y, $i, $j + 1, $mat, $dp);

        // We store the result of going west
        $maxValue = $dp[$i][$j+1]['maxValue'];
        $startValue = $dp[$i][$j+1]['start'];
        $path = $dp[$i][$j+1]['path'];
    }

    //If we can go east, compute the next cell in the way
    if ($j > 0 && ($mat[$i][$j] > $mat[$i][$j - 1]))
    {
        // We compute the next position before this one
        findLongestFromACell($x, $y, $i, $j - 1, $mat, $dp);

        // If we get better result, we store the result of going east
        if (($dp[$i][$j-1]['maxValue'] > $maxValue) || ($dp[$i][$j-1]['maxValue'] == $maxValue && $dp[$i][$j-1]['start'] < $startValue))
        {
            $maxValue = $dp[$i][$j-1]['maxValue'];
            $startValue = $dp[$i][$j-1]['start'];
            $path = $dp[$i][$j-1]['path'];
        }
    }

    // If we can go north, compute the next cell in the way
    if ($i > 0 && ($mat[$i][$j] > $mat[$i - 1][$j]))
    {
        // We compute the next position before this one
        findLongestFromACell($x, $y, $i - 1, $j, $mat, $dp);

        // If we get better result, we store the result of going north
        if (($dp[$i-1][$j]['maxValue'] > $maxValue) || ($dp[$i-1][$j]['maxValue'] == $maxValue && $dp[$i-1][$j]['start'] < $startValue))
        {
            $maxValue = $dp[$i-1][$j]['maxValue'];
            $startValue = $dp[$i-1][$j]['start'];
            $path = $dp[$i-1][$j]['path'];
        }

    }

    // If we can go south, compute the next cell in the way
    if ($i < $x - 1 && ($mat[$i][$j] > $mat[$i + 1][$j]))
    {
        // We compute the next position before this one
        findLongestFromACell($x, $y, $i + 1, $j, $mat, $dp);

        // If we get better result, we store the result of going south
        if (($dp[$i+1][$j]['maxValue'] > $maxValue) || ($dp[$i+1][$j]['maxValue'] == $maxValue && $dp[$i+1][$j]['start'] < $startValue))
        {
            $maxValue = $dp[$i+1][$j]['maxValue'];
            $startValue = $dp[$i+1][$j]['start'];
            $path = $dp[$i+1][$j]['path'];
        }
    }

    // If we have found a path, we add this position to the path already found from this position
    if ($maxValue > 0)
    {
        $dp[$i][$j] = ['maxValue' => 1 + $maxValue];
        $dp[$i][$j]['start'] = $startValue;
        $dp[$i][$j]['path'] = $path;
        array_unshift($dp[$i][$j]['path'], $mat[$i][$j]);
    } else
    {
        // If this cell is a dead end
        $dp[$i][$j] = ['maxValue' => 1];
        $dp[$i][$j]['start'] = $mat[$i][$j];
        $dp[$i][$j]['path'][] = $mat[$i][$j];
    }

    // We return the longest path from this cell
    return;
}

/**
 * @param &$mat - reference to the Map's matrix
 * @param $x - Dimesion x or number of lines of the map's matrix
 * @param $y - Dimesion y or number of columns of the map's matrix
 * @param $start -
 * @return array - That conteins:
                        'maxValue' - int - that is the number of steps of the longest path
                        'difValue' - int - that is the diference between the first value of the path and the last one
                        'path' - array - all the values of the steps of the path
 *
 */
function finLongestOverAll(&$mat, $x, $y, $start)
{
    $result = ['maxValue' => 0, 'difValue' => 0, 'path' => []];

    // We create a matrix where we are going to store the results
    for ($i = 0; $i < $x; $i++)
        $dp[$i] = array_fill(0, $y, -1);

    for ($i = 0; $i < $x; $i++)
    {
        for ($j = 0; $j < $y; $j++)
        {
            // If this cell has not be computed, else we don't do nothing because is part of a longer path already computed
            if ($dp[$i][$j] == -1)
            {
                // The max steps of the actual path is already bigger, than the one we can found in this position
                if ($result['maxValue'] < ($mat[$i][$j] + 1 - $start))
                {
                    findLongestFromACell($x, $y, $i, $j, $mat, $dp);

                    // We compare the result of the compute of the last position with al best path found at the moment, and if the new is better we stored it and forget the last one.
                    if (($dp[$i][$j]['maxValue'] > $result['maxValue']) || ($dp[$i][$j]['maxValue'] == $result['maxValue'] && ($mat[$i][$j] - $dp[$i][$j]['start']) > $result['difValue']))
                    {
                        $result['maxValue'] = $dp[$i][$j]['maxValue'];
                        $result['difValue'] = $mat[$i][$j] - $dp[$i][$j]['start'];
                        $result['path'] = $dp[$i][$j]['path'];
                    }
                }
            }
        }
    }
    return $result;
}

$matrix = [];

//We read the matrix from a file
$arrayIndexX = -1;
if ($file = fopen($_POST['filename'], 'r')){
    while (!feof($file)){
        $line = fgets($file);
        if ($arrayIndexX == -1){
            $dimensions = explode(' ', $line);
            $x = $dimensions[0];
            $y = $dimensions[1];
        }else{
            $arrayIndexY = 0;
            $row = explode(' ', $line);
            foreach ($row as $element){
                $matrix[$arrayIndexX][$arrayIndexY] = (int)$element;
                $arrayIndexY++;
            }
        }
        $arrayIndexX++;
    }
    fclose($file);
}


//We ask for the best path
$result = finLongestOverAll($matrix, $arrayIndexX, $arrayIndexY, 0);
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
<div class="col-md-12 p-lg-5 mx-auto my-5">
    <?php
    //We show the data of best path
    echo("<br/>Dimension X: " . $x . " - Dimension Y: " . $y);
    echo("<br/>Max Path: " . $result['maxValue'] . " - Drop: " . $result['difValue'] . " - Path: ");
    foreach ($result['path'] as $step)
    {
        echo($step . ", ");
    }
    ?>
    <hr/>
    <table class="table table-bordered">
        <tbody>
        <?php
        //We show the matrix generated on the screen
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
</div>
</body>
</html>
