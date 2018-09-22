<?php

class Pagination
{
    //Пагинация
    public static function pagination($countItems, $page, $elementsOnPage, $reviewSort = false, $domain)
    {
        if ($page == 0)
        {
            $page = 1;
        }

        $prev = $page - 1;
        $next = $page + 1;
        $paginate = '';
        $limit = $elementsOnPage;
        $lastpage = ceil($countItems / $limit);
        $LastPagem1 = $lastpage - 1;
        $stages = 2;

        if($reviewSort)
        {
            $reviewSort = '&sort='.$reviewSort;
        }

        if($lastpage > 1)
        {
            if ($page > 1)
            {
                $paginate.= '<li><a href="'.route('reviews', $domain).'?page='.$prev.$reviewSort.'" class="border-left">Предыдущая</a></li>';
            }
            else
            {
                $paginate.= '<li><a href="javascript:void(0);" class="disabled border-left">Предыдущая</a></li>';
            }

            if ($lastpage < 7 + ($stages * 2))
            {

                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                    {
                        $paginate.= '<li><a href="javascript:void(0);" class="active">'. $counter .'</a></li>';
                    }
                    else
                    {
                        $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $counter .$reviewSort.'">'. $counter .'</a></li>';
                    }
                }
            }
            elseif($lastpage > 5 + ($stages * 2))
            {

                if($page < 1 + ($stages * 2))
                {
                    for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                    {
                        if ($counter == $page)
                        {
                            $paginate.= '<li><a href="javascript:void(0);" class="active">'. $counter .'</a></li>';
                        }
                        else
                        {
                            $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $counter .$reviewSort.'">'. $counter .'</a></li>';
                        }

                    }
                    $paginate.= '<li><a href="javascript:void(0);" class="disabled">...</a></li>';
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $LastPagem1 .$reviewSort.'">'. $LastPagem1 .'</a></li>';
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $lastpage .$reviewSort.'">'. $lastpage .'</a></li>';
                }

                elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) //
                {
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page=1'.$reviewSort.'">1</a></li>';
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page=2'.$reviewSort.'">2</a></li>';
                    $paginate.= '<li><a href="javascript:void(0);" class="disabled">...</a></li>';
                    for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                    {
                        if ($counter == $page)
                        {
                            $paginate.= '<li><a href="javascript:void(0);" class="active">'. $counter .'</a></li>';
                        }
                        else
                        {
                            $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $counter .$reviewSort.'">'. $counter .'</a></li>';
                        }

                    }
                    $paginate.= '<li><a href="javascript:void(0);" class="disabled">...</a></li>';
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $LastPagem1 .$reviewSort.'">'. $LastPagem1 .'</a></li>';
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $lastpage .$reviewSort.'">'. $lastpage .'</a></li>';
                }

                else
                {
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page=1'.$reviewSort .'">1</a></li>';
                    $paginate.= '<li><a href="'. route('reviews', $domain) .'?page=2'.$reviewSort.'">2</a></li>';
                    $paginate.= '<li><a href="javascript:void(0);" class="disabled">...</a></li>';
                    for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page){
                            $paginate.= '<li><a href="javascript:void(0);" class="active">'. $counter .'</a></li>';
                        }
                        else{
                            $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $counter .$reviewSort.'">'. $counter .'</a></li>';
                        }
                    }

                }
            }

            if ($page < $counter - 1)
            {
                $paginate.= '<li><a href="'. route('reviews', $domain) .'?page='. $next .$reviewSort.'">Следующая</a></li>';
            }
            else
            {
                $paginate.= '<li><a href="javascript:void(0);" class="disabled">Следующая</a></li>';
            }
        }

        return $paginate;
    }

}