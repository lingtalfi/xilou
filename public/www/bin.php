<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Chi Hoang (info@chihoang.de)
 *  All rights reserved
 *
 ***************************************************************/


require_once __DIR__ . "/../init.php";

class binPacking
{

    var $BinHeight = 15;
    //~ var $ElementsCopy = array ( 0.1, 0.5, 1, 3, 4, 5, 7, 8, 1, 3, 4, 5, 7, 8, 5, 6, 4, 3, 8, 5);
    //~ var $ElementsCopy = array ( 0.1, 0.5, 1, 3, 4, 5, 7, 8, 1, 3, 4, 5, 7, 8, 5, 6, 4, 3, 8, 5,7,11,13, 14, 15, 2,1,5, 10, 1, 4, 4, 1, 6, 3, 2, 1, 3);
    var $ElementsCopy = array(0.1, 0.5, 1, 3, 4, 5, 7, 8, 1, 3, 4, 5, 7, 8, 5, 6, 4, 3, 8, 5, 7, 11, 13, 14, 15, 2, 1, 5, 10, 1, 4, 4, 1, 6, 3, 2, 1, 3, 5, 7, 8, 1, 3, 4, 5, 7, 8, 5, 6, 4, 3, 8, 5);
    var $Bins;

    function sortAsc()
    {
        sort($this->ElementsCopy, SORT_NUMERIC);
    }

    function sortDesc()
    {
        rsort($this->ElementsCopy, SORT_NUMERIC);
    }

    function shuffle()
    {
        shuffle($this->ElementsCopy);
    }

    function random_float($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    function aoc()
    {

        $alfa = 1.0; // The importance of the previous trails
        $beta = 1.0; // The importance of the durations
        $rho = 0.1;  // The decay rate of the pheromone trails
        $asymptoteFactor = 0.9; // The sharpness of the reward as the solutions approach the best solution
        $numAnts = 20;
        $numWaves = 80;
        $numActive = $end = count($this->ElementsCopy);
        $mark = array();
        $element = 0;
        $dur = array();
        $nextPher = array();
        $bestPath = array();

        for ($i = 0; $i < $numActive; ++$i) {
            for ($j = 0; $j < $numActive; ++$j) {
                $pher[$i][$j] = 1;
                $nextPher[$i][$j] = 0.0;
            }
        }

        for ($i = 0; $i < $numActive; ++$i) {
            for ($j = 0; $j < $numActive; ++$j) {
                if ($i != $j) {
                    $dur[$i][$j] = $this->ElementsCopy[$i] + $this->ElementsCopy[$j];
                } else {
                    $dur[$i][$j] = $this->ElementsCopy[$i];
                }
            }
        }

        for ($i = 0; $i < count($this->ElementsCopy); ++$i) {
            $mark[$i] = false;
        }

        for ($wave = 0; $wave < $numWaves; ++$wave) {

            $startNode = 0;
            $numActive = count($this->ElementsCopy);
            $numSteps = $numActive - 1;
            $numValidDests = $numActive;
            $bestTrip = 0;
            $currDist = 0;
            $currPath = array();
            $bestPath = array();

            for ($i = 0; $i < $numActive; ++$i) {
                $visited[$i] = false;
            }

            $curr = $startNode;
            $cumProb = 0.0;

            for ($ant = 0; $ant < $numAnts; ++$ant) {

                $cumProb = 0.0;
                for ($next = 1; $next < $numValidDests; ++$next) {
                    if (!$visited[$next] && !$mark[$next]) {
                        $prob[$next] = pow($pher[$curr][$next], $alfa) * pow($dur[$curr][$next], 0.0 - $beta);
                        $cumProb += $prob[$next];
                    }
                }
                $guess = $this->random_float(0, 1) * $cumProb;
                $nextI = 0;
                for ($next = 1; $next < $numValidDests; ++$next) {
                    if (!$visited[$next] && !$mark[$next]) {
                        $curr = $next;
                        $guess -= $prob[$next];
                        if ($guess < 0) {
                            $curr = $next;
                            break;
                        }
                    }
                }

                $currDist = 0;
                $currPath = array();
                $currPath[0] = $curr;
                $currDist += $dur[$curr][$curr];
                $numSteps = $numActive - 1;

                for ($step = 0; $step < $numSteps; ++$step) {

                    $visited[$curr] = true;
                    $cumProb = 0.0;
                    for ($next = 1; $next < $numValidDests; ++$next) {
                        if (!$visited[$next] && !$mark[$next]) {
                            $prob[$next] = pow($pher[$curr][$next], $alfa) * pow($dur[$curr][$next], 0.0 - $beta);
                            $cumProb += $prob[$next];
                        }
                    }
                    $guess = $this->random_float(0, 1) * $cumProb;
                    $nextI = 0;
                    for ($next = 1; $next < $numValidDests; ++$next) {
                        if (!$visited[$next] && !$mark[$next]) {
                            $nextI = $next;
                            $guess -= $prob[$next];
                            if ($guess < 0.0) {
                                break;
                            }
                        }
                    }

                    if ($currDist + $dur[$curr][$nextI] - $dur[$curr][$curr] <= $this->BinHeight && !in_array($nextI, $currPath) && !$mark[$nextI]) {

                        $currDist += $dur[$curr][$nextI] - $dur[$curr][$curr];
                        $currPath[$step + 1] = $nextI;
                        $curr = $nextI;

                        if ($currDist > $bestTrip && $currDist <= $this->BinHeight) {
                            $bestPath = $currPath;
                            $bestTrip = $currDist;
                        }
                    }
                }
                $currPath = array_values($currPath);
                for ($i = 0; $steps = count($currPath) - 1, $i < $steps; ++$i) {
                    //echo "\n".$currDist - $asymptoteFactor * $bestTrip."\n";
                    if ($numAnts * ($currDist - $asymptoteFactor * $bestTrip)) {
                        $nextPher[$currPath[$i]][$currPath[$i + 1]] += ($bestTrip - $asymptoteFactor * $bestTrip) / $numAnts * ($currDist - $asymptoteFactor * $bestTrip);
                    }
                }
            }

            for ($i = 0; $i < $numActive; ++$i) {
                for ($j = 0; $j < $numActive; ++$j) {
                    $pher[$i][$j] = $pher[$i][$j] * (1.0 - $rho) + $rho * $nextPher[$i][$j];
                    $nextPher[$i][$j] = 0.0;
                }
            }

            //~ echo "\nbest:".$bestTrip;
            //~ a($bestPath);
            //~ a($currPath);

            if (empty($bestPath)) {
                $bestPath = $currPath;
            }

            $c = 0;
            foreach ($bestPath as $k => $v) {
                if (!$mark[$v]) {
                    $c++;
                }
            }
            //~ echo "\nc:".$c."\n";
            if ($c == count($bestPath)) {
                foreach ($bestPath as $k => $v) {
                    $mark[$v] = true;
                }
                $this->Bins[] = $bestPath;
            }
        }

        //~ a($this->ElementsCopy);
        //~ a($bestPath);
//        a($mark);
        //~ a($nextPher);
        //~ a($pher);
        //a($prob);
        //a($dur);
    }

    function checkAoc()
    {
        $c = 0;
        $totalLoss = 0;
        a(count($this->Bins));
        foreach ($this->Bins as $index => $bin) {
            $sum = 0;
            $c++;
            foreach ($bin as $binNumber => $element) {
                $sum += $this->ElementsCopy[$element];
            }
            $totalLoss += $this->BinHeight - $sum;
            echo "\nBin: $c\n";
//            a($bin);
            echo "\nSum: $sum\n";
        }
        a("total loss: " . $totalLoss);
    }

    function nextFit()
    {

        $this->Bins = array();
        $BinNumber = 0;
        $BinCount = 0;

        // Loop through each Element and place in a Bin
        for ($i = 0; $end = sizeOf($this->ElementsCopy), $i < $end; ++$i) {

            if ($BinCount + $this->ElementsCopy[$i] > $this->BinHeight) {
                $BinNumber++;
                $BinCount = 0;
            }

            // Place task
            $this->Bins[$BinNumber][] = $this->ElementsCopy[$i];
            // Keep track of how much is stored in this bin
            $BinCount += $this->ElementsCopy[$i];
        }
    }

    function firstFit()
    {

        $this->Bins[] = array();
        $BinNumber = 0;
        $BinElement = 0;

        // Loop through each Element and place in a Bin
        for ($i = 0; $end = sizeOf($this->ElementsCopy), $i < $end; ++$i) {

            $BinCount = 0;
            $bPlaced = false;

            // Loops through each Bin to find the first available spot
            for ($j = 0; $j <= $BinNumber; ++$j) {

                // Count the amount placed in this Bin
                $BinCount = 0;
                for ($k = 0; $BinElement = sizeOf($this->Bins[$j]), $k < $BinElement; ++$k) {
                    $BinCount += $this->Bins[$j][$k];
                }
                if ($BinCount + $this->ElementsCopy[$i] <= $this->BinHeight) {
                    // There's room for this Element
                    $this->Bins[$j][$BinElement] = $this->ElementsCopy[$i];
                    $this->Bins[$j][] = 0;
                    $bPlaced = true;
                }
            }

            if ($bPlaced == false) {
                //There wasn't room for the Element in any existing Bin
                //Create a new Bin
                $this->Bins[][] = $this->ElementsCopy[$i];
                $BinNumber++;
            }
        }

        // All Elements have been place, now we go back and remove unused Elements
        foreach ($this->Bins as $bin => $element) {
            foreach ($element as $place => $value) {
                if ($value === 0) unset($this->Bins[$bin][$place]);
            }
        }
    }

    function worstFit()
    {

        $this->Bins[] = array();
        $BinNumber = 0;

        // Loop through each Element and place in a Bin
        for ($i = 0; $end = sizeOf($this->ElementsCopy), $i < $end; ++$i) {

            $WorstBin = -1;
            $WorstBinAmount = $this->BinHeight + 1;

            for ($j = 0; $j <= $BinNumber; ++$j) {

                // Count the amount placed in this Bin
                $BinCount = 0;
                for ($k = 0; $BinElement = sizeOf($this->Bins[$j]), $k < $BinElement; ++$k) {
                    $BinCount += $this->Bins[$j][$k];
                }

                // Find the least full Bin that can hold this Element
                if ($WorstBinAmount > $BinCount && $BinCount + $this->ElementsCopy[$i] <= $this->BinHeight) {
                    $WorstBinAmount = $BinCount;
                    $WorstBin = $j;
                }
            }

            if ($WorstBin == -1) {
                //There wasn't room for the Element in any existing Bin
                //Create a new Bin
                $BinNumber++;
                $this->Bins[$BinNumber][] = $this->ElementsCopy[$i];
            } else {
                //There's room for this Element in an existing Bin
                //Place Element in "Worst Bin"
                $this->Bins[$WorstBin][] = $this->ElementsCopy[$i];
            }
        }
    }

    function bestFit()
    {

        $this->Bins[] = array();
        $BinNumber = 0;
        $BinElement = 0;

        // Loop through each Element and place in a Bin
        for ($i = 0; $end = sizeOf($this->ElementsCopy), $i < $end; ++$i) {

            $BestBin = -1;
            $BestBinAmount = -1;

            for ($j = 0; $j <= $BinNumber; ++$j) {

                // Count the amount placed in this Bin
                $BinCount = 0;
                for ($k = 0; $BinElement = sizeOf($this->Bins[$j]), $k < $BinElement; ++$k) {
                    $BinCount += $this->Bins[$j][$k];
                }

                // Find the most full Bin that can hold this Element
                if ($BestBinAmount < $BinCount && $BinCount + $this->ElementsCopy[$i] <= $this->BinHeight) {
                    $BestBinAmount = $BinCount;
                    $BestBin = $j;
                }
            }

            if ($BestBin == -1) {
                //There wasn't room for the Element in any existing Bin
                //Create a new Bin
                $BinNumber++;
                $this->Bins[$BinNumber][] = $this->ElementsCopy[$i];
            } else {
                //There's room for this Element in an existing Bin
                //Place Element in "Best Bin"
                $this->Bins[$BestBin][] = $this->ElementsCopy[$i];
            }
        }
    }
}

$lkw = new binPacking();
//~ $lkw->sortDesc();
//~ $lkw->sortAsc();
//~ $lkw->shuffle();
//~ a($lkw->ElementsCopy);
//~ $lkw->nextFit();
//$lkw->firstFit(); // 85.3
//$lkw->worstFit(); // 90.3
// $lkw->bestFit(); // 90.3
$lkw->aoc(); // 15.4
$lkw->checkAoc();
//~ a($lkw->Bins);


