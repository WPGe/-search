<?php
/**
 * Created by PhpStorm.
 * User: Vanya
 * Date: 22.12.2019
 * Time: 0:47
 */

namespace App\Http\Controllers;

use App\Slider;

class IndexController extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Slider $slider)
    {

        $this->data['selection'] = [11, 3, 45, 22, 47, 100, 33, 22, 0, 61];

        $selection = [11, 3, 45, 22, 47, 100, 33, 22, 0, 61];

        $this->reverse($selection);

        echo '<pre>';
        print_r($selection);
        echo '</pre>';

        return view('pages.index', $this->data);
    }

    public function reverse(&$selection)
    {
        $count = count($selection);
        for($i = $count-1, $j = 0; $i > $j; $i--, $j++)
        {
            $buf = $selection[$j];
            $selection[$j] = $selection[$i];
            $selection[$i] = $buf;
        }
    }

    public function binarySearch($selection, $item)
    {
        $left = 0;
        $right = count($selection)-1;
        if($selection[$right] < $item || $selection[$left] > $item)
            return 'Число не найдено';
        while($left+1 != $right)
        {
            $mid = intval(($left + ($right - $left)) / 2);
            if($selection[$mid] != $item)
            {
                if($selection[$mid] > $item)
                    $right = $mid;
                else
                    $left = $mid;
            }
            else
            {
                $search[] = $mid;
                for ($i = 1;$selection[$mid+$i] == $item; $i++)
                    $search[] = $mid + $i;
                for ($i = 1;$selection[$mid-$i] == $item; $i++)
                    $search[] = $mid - $i;
                break;
            }
        }
        if(empty($search))
            $search = 'Число не найдено';
        return $search;
    }

    public function lineSearch(&$selection, $searchItem)
    {
        $serchIndex = [];
        for($i = 0; $i < count($selection); $i++)
        {
            if($selection[$i] == $searchItem)
                $serchIndex[] = $i;
        }
        return $serchIndex;
    }

    public function selection(&$selection)
    {
        $count = count($selection);
        for($i = 0; $i < count($selection); $i++)
        {
            $value = $selection[0];
            $key = 0;
            for($j = 0; $j < $count; $j++)
            {
                if($value < $selection[$j])
                {
                    $value = $selection[$j];
                    $key = $j;
                }
            }
            $buf = $selection[$count - 1];
            $selection[$count - 1] = $value;
            $selection[$key] = $buf;
            $count--;
        }
    }

    public function vstavka(&$selection)
    {
        for($i = 0; $i < count($selection); $i++)
        {
            $buf = $selection[$i];
            for($key1 = $i - 1, $key2 = $i; $key1 >= 0; $key1--)
            {
                if($selection[$key1] > $buf)
                {
                    $selection[$key2] = $selection[$key1];
                    $selection[$key1] = $buf;
                    $key2--;
                }
            }
        }
    }

    public function sliyaniye(&$selection)
    {
        $count = count($selection);
        $firstIndicators = [];
        $secondIndicators = [];
        $array = [];
        while(empty($sort))
        {
            $arrayNumber = 0;
            $array[$arrayNumber][0] = $selection[0];
            for($i = 1; $i < $count; $i++)
            {
                if($selection[$i] >= $selection[$i - 1])
                {
                    $array[$arrayNumber][] = $selection[$i];
                }
                else
                {
                    if($arrayNumber == 0)
                    {
                        end($array[$arrayNumber]);
                        $firstIndicators[] = key($array[$arrayNumber]);
                        $arrayNumber++;
                        $array[$arrayNumber][] = $selection[$i];
                    }
                    else
                    {
                        end($array[$arrayNumber]);
                        $secondIndicators[] = key($array[$arrayNumber]);
                        $arrayNumber--;
                        $array[$arrayNumber][] = $selection[$i];
                    }
                }
            }
            end($array[$arrayNumber]);
            $secondIndicators[] = key($array[$arrayNumber]);

            if(empty($array[1]))
                $sort = 1;
            else
            {
                for($i = 0, $j = 0, $h = 0, $indicator = 0; $i < $count; )
                {
                    if(!empty($firstIndicators[$indicator]) && !empty($secondIndicators[$indicator]))
                    {
                        while($j <= $firstIndicators[$indicator] && $h <= $secondIndicators[$indicator])
                        {
                            if($array[0][$j] < $array[1][$h])
                            {
                                $selection[$i] = $array[0][$j];
                                $j++;
                            }
                            elseif($array[0][$j] > $array[1][$h])
                            {
                                $selection[$i] = $array[1][$h];
                                $h++;
                            }
                            else
                            {
                                $selection[$i] = $array[0][$j];
                                $selection[$i+1] = $array[1][$h];
                                $i++;$j++;$h++;
                            }
                            $i++;
                        }
                        if($j > $firstIndicators[$indicator] && $h <= $secondIndicators[$indicator])
                        {
                            while($h <= $secondIndicators[$indicator])
                            {
                                $selection[$i] = $array[1][$h];
                                $h++;
                                $i++;
                            }
                        }
                        elseif($j <= $firstIndicators[$indicator] && $h > $secondIndicators[$indicator])
                        {
                            while($j <= $firstIndicators[$indicator])
                            {
                                $selection[$i] = $array[0][$j];
                                $j++;
                                $i++;
                            }
                        }
                    }
                    elseif(!empty($firstIndicators[$indicator]))
                    {
                        while($j <= $firstIndicators[$indicator])
                        {
                            $selection[$i] = $array[0][$j];
                            $j++;
                            $i++;
                        }
                    }
                    elseif(!empty($secondIndicators[$indicator]))
                    {
                        while($h <= $secondIndicators[$indicator])
                        {
                            $selection[$i] = $array[1][$h];
                            $h++;
                            $i++;
                        }
                    }
                    else
                        break;
                    $indicator++;
                }
            }

            $array = [];
            $firstIndicators = [];
            $secondIndicators = [];
        }
    }
}