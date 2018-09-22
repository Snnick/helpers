<?php

class Helper
{
    // Принимает первым параметром строку
    // Вторым количество до какого максимального количества слов обрезать
    public static function getOpening($full, $words = 100, $str = 180)
    {
        $string = preg_replace('/\s|(&nbsp;)/m',' ',$full);
        $string = strip_tags( html_entity_decode($string) );
        $string = preg_replace('/\s|(&nbsp;)/m',' ',$string);
        $string = trim($string);
        return self::truncateStr($string, $words, $str);
    }

    // Принимает первым параметром строку
    // Вторым количество до какого максимального количества слов обрезать
    // Третий параметр максимальное количество символов в строке до которого обрезать
    // и является преоритетнее второго параметра
    public static function truncateStr($string, $count, $str, $suffix = '...')
    {
        $words = preg_split('/(\s+)/u', trim($string), null, PREG_SPLIT_DELIM_CAPTURE);
        $result = [];
        $i = 0;
        foreach ($words as $key => $word)
        {
            if($i <= $str)
            {
                $i += strlen($word);
                $result[$key] = $word;
            }
        }
        array_pop($result);
        $string = implode('', array_slice($result, 0, ($count * 2) - 1)) . $suffix;
        $string = ($string == $suffix) ? '' : $string;

        return $string;
    }

    // Склонение городов
    public static function getDeclineCity($city = '')
    {

        $ending =
            [
                //группа с окончанием А
                '[к][а]' => 'ки',
                '[а]' => 'ы',

                '[в]' => 'ва',
                '[д]' => 'да',

                //группа с окончанием Е
                '[ь][е]' => 'ья',
                '[о][е]' => 'ого',

                '[и]' => 'ок',

                //группа с окончанием Й
                '[и][й]' => 'ого',
                '[а][й]' => 'ая',

                '[к]' => 'ка',
                '[н]' => 'на',
                '[о]' => 'а',
                '[р]' => 'ра',
                '[т]' => 'та',

                '[е][ц]' => 'ца',

                '[ь]' => 'я',

                //группа с окончанием Ы
                '[с][ы]' => 'с',
                '[ы]' => 'ов',

                //группа с окончанием Я
                '[ы][я]' => 'ыи',
                '[и][я]' => 'ии',
            ];

        foreach ($ending as $key => $val)
        {
            if(preg_match("/([а-яА-ЯёЁ]+)($key$)/u", $city))
            {
                $city =  preg_replace("/([а-яА-ЯёЁ]+)($key$)/u", '${1}'.$val, $city);
                return $city;
            }
        }
        return $city;
    }


    //Транслитерация
    public static function translit($s)
    {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z',
            'и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t',
            'у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch',
            'ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }


    //Мерж масивов, полезно для хлебных крошек
    public static function arrayMerge($array1, $array2)
    {
        $newArray1 = explode(',', $array1);
        $newArray2 = explode(',', $array2);

        $newArrayMerge = array_combine($newArray1, $newArray2);
        return $newArrayMerge;
    }

    // Генерация кода
    public static function generateCode($length = 10)
    {
        $num = range(0, 9);
        $alf = range('a', 'z');
        $_alf = range('A', 'Z');
        $symbols = array_merge($num, $alf, $_alf);
        shuffle($symbols);
        $code_array = array_slice($symbols, 0, (int)$length);
        $code = implode("", $code_array);

        return $code;
    }

    // Уменьшение фото в пикселях до нужного размера
    public static function resize($image, $w_o = false, $h_o = false)
    {
        if (($w_o < 0) || ($h_o < 0)) {
            echo "Некорректные входные параметры";
            return false;
        }
        list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
        $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
        $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
        if ($ext) {
            $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
            $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
        } else {
            echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
            return false;
        }
        /* Если указать только 1 параметр, то второй подстроится пропорционально */
        if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
        if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
        $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
        imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
        $func = 'image'.$ext; // Получаем функция для сохранения результата
        return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
    }

    // Наложение вотермарка на фото по центру
    public static function watermarkImage($template, $logo)
    {
        list($owidth,$oheight) = getimagesize($template);
        // задаем размеры для выходного изображения
        $width = $owidth;
        $height = $oheight;

        // создаем выходное изображение размерами, указанными выше
        $im = imagecreatetruecolor($width, $height);
        $img_src = imagecreatefromjpeg($template);
        // наложение на выходное изображение, исходного
        imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);

        $watermark = (preg_match('/(jpg)/',$logo)) ? imagecreatefromjpeg($logo) : imagecreatefrompng($logo);

        // получаем размеры водяного знака
        list($w_width, $w_height) = getimagesize($logo);

        // определяем позицию расположения водяного знака
        $pos_x = ($width-$w_width)/2;
        $pos_y = ($height-$w_height)/2;
        // накладываем водяной знак
        imagecopy(
        // destination
            $im,
            // source
            $watermark,
            // destination x and y
            $pos_x, $pos_y,
            // source x and y
            0, 0,
            // width and height of the area of the source to copy
            $w_width, $w_height);

        // сохраняем выходное изображение, уже с водяным знаком в формате jpg и качеством 100
        imagejpeg($im, $template, 100);
    }
}