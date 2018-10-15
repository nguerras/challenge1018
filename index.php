<?php
/**
 * Created by PhpStorm.
 * User: Noemi
 * Date: 15/10/2018
 */


function findLongestFromACell($x, $y, $i, $j, $mat, $dp)
{
    $pathsFromCell = [];

    if ($dp[$i][$j] != -1)
    {
        return $dp[$i][$j];
    }

    if ($j < $y - 1 && ($mat[$i][$j] > $mat[$i][$j + 1]))
    {
        $pathsFromCell[] = findLongestFromACell($x, $y, $i, $j + 1, $mat, $dp);
    }

    if ($j > 0 && ($mat[$i][$j] > $mat[$i][$j - 1]))
    {
        $pathsFromCell[] = findLongestFromACell($x, $y, $i, $j - 1, $mat, $dp);
    }

    if ($i > 0 && ($mat[$i][$j] > $mat[$i - 1][$j]))
    {
        $pathsFromCell[] = findLongestFromACell($x, $y, $i - 1, $j, $mat, $dp);
    }

    if ($i < $x - 1 && ($mat[$i][$j] > $mat[$i + 1][$j]))
    {
        $pathsFromCell[] = findLongestFromACell($x, $y, $i + 1, $j, $mat, $dp);
    }

    if (count($pathsFromCell) > 0)
    {
        $dp[$i][$j] = 1 + max($pathsFromCell);
    } else
    {
        $dp[$i][$j] = 1;
    }

    return $dp[$i][$j];
}


function finLongestOverAll($mat, $x, $y)
{
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
                echo("<br/>Sizing from position " . $i . " " . $j . "<br/>");
                $dp[$i][$j] = findLongestFromACell($x, $y, $i, $j, $mat, $dp);
                echo("Max path:" . $dp[$i][$j] . "<br/>");
            }
        }
    }

    return $dp;
}


$matrix = [
    [4, 8, 7, 3],
    [2, 5, 9, 3],
    [6, 3, 2, 5],
    [4, 4, 1, 6],
];

var_dump($matrix);
echo("<br/>");
$result = finLongestOverAll($matrix, 4, 4);

?>