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

    if (is_array($dp[$i][$j]))
    {
        return $dp[$i][$j];
    }

    if ($j < $y - 1 && ($mat[$i][$j] > $mat[$i][$j + 1]))
    {
        $aux = findLongestFromACell($x, $y, $i, $j + 1, $mat, $dp);
        $maxValue = $aux['maxValue'];
        $startValue = $aux['start'];
    }

    if ($j > 0 && ($mat[$i][$j] > $mat[$i][$j - 1]))
    {
        $aux = findLongestFromACell($x, $y, $i, $j - 1, $mat, $dp);
        if (($aux['maxValue'] > $maxValue) || ($aux['maxValue'] == $maxValue && $aux['start'] < $startValue))
        {
            $maxValue = $aux['maxValue'];
            $startValue = $aux['start'];
        }
    }

    if ($i > 0 && ($mat[$i][$j] > $mat[$i - 1][$j]))
    {
        $aux = findLongestFromACell($x, $y, $i - 1, $j, $mat, $dp);
        if (($aux['maxValue'] > $maxValue) || ($aux['maxValue'] == $maxValue && $aux['start'] < $startValue))
        {
            $maxValue = $aux['maxValue'];
            $startValue = $aux['start'];
        }

    }

    if ($i < $x - 1 && ($mat[$i][$j] > $mat[$i + 1][$j]))
    {
        $aux = findLongestFromACell($x, $y, $i + 1, $j, $mat, $dp);
        if (($aux['maxValue'] > $maxValue) || ($aux['maxValue'] == $maxValue && $aux['start'] < $startValue))
        {
            $maxValue = $aux['maxValue'];
            $startValue = $aux['start'];
        }
    }

    if ($maxValue > 0)
    {
        $dp[$i][$j] = ['maxValue' => 1 + $maxValue];
        $dp[$i][$j]['start'] = $startValue;
    } else
    {
        $dp[$i][$j] = ['maxValue' => 1];
        $dp[$i][$j]['start'] = $mat[$i][$j];
    }

    return $dp[$i][$j];
}


function finLongestOverAll($mat, $x, $y)
{
    $maxValue = 0;
    $difValue = 0;

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
                if (($dp[$i][$j]['maxValue'] > $maxValue) || ($dp[$i][$j]['maxValue'] == $maxValue && ($mat[$i][$j] - $dp[$i][$j]['start']) > $difValue))
                {
                    $maxValue = $dp[$i][$j]['maxValue'];
                    $difValue = $mat[$i][$j] - $dp[$i][$j]['start'];
                }
            }
        }
    }
    echo("<br/>Max Path:" . $maxValue . " and Steepest:" . $difValue);
    return $dp;
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