<?php

class MenuRecursive
{

    // Полезная рекурсия для вывода меню любой вложености
    public static function maketree(&$arr,$pid = 0)
    {
        //Массив передается по ссылке, чтобы мы могли сразу вытаскивать использованные компоненты. Еще можно отсортировать по parent.
        $out = array();
        foreach($arr as $n => $row)
        {
            if($row['parent_id'] == $pid)
            {
                //Если родитель равен запрашиваемому, такой элемент нам подходит
                $r = $row;
                unset($arr[$n]); //Удаление вставленного элемента.
                $children = self::maketree($arr, $row['id']);// выбираем детей из массива
                if (count($children) > 0)
                {
                    //если нашли больше 0 детей, создаем соответствующий ключ
                    $r['children'] = $children;
                }
                $out[] = $r;
            }
        }
        return $out;
    }
}