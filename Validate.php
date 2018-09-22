<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 15.04.2017
 * Time: 14:46
 */

namespace App\Helpers;

class Validate
{
    //! Имя невалидного ключа
    protected static $errorKey = '';

    public static $allowedImagesExtensions = array('jpg','jpeg','png','gif');

    //! Валидация имени пользователя
    public static function login($data)
    {
        //! Латиница, цифры, "_", "-". /^[a-z0-9\\_\\-]{3,32}$/i
        return (bool)preg_match('/^[a-z0-9\_\-\.]{3,32}$/i', $data);
    }

    //! Валидация скайпа пользователя
    public static function skype($data)
    {
        //! Латиница, цифры, "_", "-". /^[a-z0-9\\_\\-]{3,32}$/i
        return (bool)preg_match('/^[a-z0-9\_\-\.]{3,32}$/i', $data);
    }

    public static function icq($data)
    {
        return (bool)preg_match('/^\d{4,9}$/i', $data);
    }

    public static function subdomain($data, $minLength = 3)
    {
        $len = strlen($data);
        return $len >= $minLength && $len <= 18 && (bool)preg_match('/^([a-zA-Z0-9]+(?:\-(?:[a-zA-Z0-9])+)*(?:[a-zA-Z0-9])*)$/i', $data);
    }

    public static function smsSenderName($data)
    {
        return (bool)(preg_match('/^[a-zA-Z0-9]+([ ]*[a-zA-Z0-9]+)*$/i', $data) && strlen($data) <= 11);
    }

    /**
     * @param string|array $data - телефон или массив телефонов
     * @param bool $allowShort
     * @return bool
     */
    public static function phone($data, $allowShort = false)
    {
        $regexp = '/^\d{' . (($allowShort) ? '5' : '10') . ',17}$/i';
//		foreach(func_get_args() as $value)
        {
            if(is_array($data))        // если передали массив, то проверяем каждое значение из массива
            {
                foreach($data as $val)
                {
                    if(!self::phone($val, $allowShort))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if(!(bool)preg_match($regexp, $data))
                {
                    return false;
                }
            }
        }
        return true;

        // return (bool)preg_match('/^[1-9]\d{9,24}$/i', $data);
    }

    //! Валидация e-mail
    public static function email($data)
    {
        return (bool)filter_var($data, FILTER_VALIDATE_EMAIL);
    }

    //! Валидация username и email
    public static function username($data)
    {
        if (Validate::email($data) || Validate::login($data)) return true;

        return false;
    }

    public static function md5($data)
    {
        //! (bool)preg_match('/^[\\da-f]{32}$/', $data)
        return (bool)preg_match('/^[\da-f]{32}$/', $data);
    }

    //! Валидация пароля
    public static function password($data)
    {
        foreach(func_get_args() as $value)
        {
            if (preg_match('/^.{6,32}$/', $value)) continue;
            return false;
        }
        return true;
    }

    //! Валидация ID пользователя
    public static function userId($data)
    {
        //! @ref Validate::id()
        return self::id($data);
    }

    //! Валидация числового ID. !!! 0 - НЕ ВАЛИДНЫЙ ID !!!
    /**
     * @param $data1..$dataN числовой ID
     * @return bool
     */
    public static function id($data1, $dataN = null)
    {
        foreach(func_get_args() as $value)
        {
            if(is_array($value))        // если передали массив, то проверяем каждое значение из массива
            {
                foreach($value as $val)
                    if(!self::id($val))
                        return false;
            }
            else
            {
                if (self::unsignedInt($value) && $value > 0) continue;
                return false;
            }
        }
        return true;
    }

    //! Валидация целого числа
    public static function int($data)
    {
        foreach(func_get_args() as $value)
        {
            if (is_bool($value)) return false;
            if (preg_match('/^\-?\d+$/', $value)) continue;
            return false;
        }
        return true;
    }

    public static function unsignedInt($data)
    {
        foreach(func_get_args() as $value)
        {
            if(is_array($value))        // если передали массив, то проверяем каждое значение из массива
            {
                foreach($value as $val)
                {
                    if(!self::unsignedInt($val))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if (self::int($value) && $value >= 0)
                    continue;
                return false;
            }
        }
        return true;
    }

    public static function roles($data)
    {
        if(is_array($data))
        {
            foreach($data as $role)
            {
                if(!self::validateOneRole($role))
                {
                    return false;
                }
            }
            return true;
        }
        else
        {
            return self::validateOneRole($data);
        }
    }

    private static function validateOneRole($role)
    {
        return in_array($role, array('admin', 'manager', 'user'));
    }


    //! Валидация float
    public static function float($data)
    {
        //! (bool)preg_match('/^\\d+$/',$data)

        return (bool)preg_match('/^[+\-]?\d+(?:\.\d{1,64})?$/', $data);

        //return (bool)preg_match('/^\d+$/',$data);
    }

    //! Валидация беззнакового float
    public static function unsignedFloat($data)
    {
        foreach(func_get_args() as $value)
        {
            if(is_array($value))        // если передали массив, то проверяем каждое значение из массива
            {
                foreach($value as $val)
                {
                    if(!self::unsignedFloat($val))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if (self::float($value) && $value >= 0)
                    continue;
                return false;
            }
        }
        return true;

        //! return (self::float($data) && $data >=0)
        // return (self::float($data) && $data >=0);
    }

    //! Валидация логических данных. True возвращают следующие значения $data: true, false, 1, 0, "1", "0"
    public static function logic($data)
    {
        foreach(func_get_args() as $value)
        {
            if (is_bool($value)) continue;
            if (Validate::int($value) && ((int)$value == 1 || (int)$value == 0)) continue;
            return false;
        }
        return true;
    }

    public static function sex($sex)
    {
        return in_array($sex, array(0,1));
    }
    //! Валидация массива данных
    /**
     * @param array $data Ассоциативный массив данных
     * @return bool true - если все данные валидны, иначе - false
     */
    public static function all($data)
    {
        self::$errorKey = '';
        if (!is_array($data) || empty($data)) return false;

        foreach ($data as $key => $value)
        {
            if (!self::$key($value))
            {
                self::$errorKey = $key;
                return false;
            }
        }
        return true;
    }

    //! Получить невалидный ключ при обработке @ref Validate::all()
    /**
     * @return string|bool невалидный ключ, если он есть (один из ключей невалидный), иначе - false
     */
    public static function getErrorKey()
    {
        return self::$errorKey;
    }

    //! Валидация даты
    public static function date($data)
    {
        foreach(func_get_args() as $value)
        {
            if (preg_match('/^(0?[1-9]|[1-2][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(19|20|21|22)\d{2}$/', $value)) continue;
            return false;
        }
        return true;
    }

    //! Валидация даты и времени
    public static function dateTime($data)
    {
        foreach(func_get_args() as $value)
        {
            if (preg_match('/^(0?[1-9]|[1-2][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(19|20|21|22)\d{2} (0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9])(:0?[0-9]|[1-5][0-9])?$/', $value)) continue;
            return false;
        }
        return true;
    }

    //! Валидация времени
    public static function time($data)
    {
        foreach(func_get_args() as $value)
        {
            if (preg_match('/^(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9])(:0?[0-9]|[1-5][0-9])?$/', $value)) continue;
            return false;
        }
        return true;
    }


    public static function url($data)
    {
        $urlregex = '/^((https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?)?(\/?([a-z0-9;:@\/&%=+\$_.-]\.?)+)*\/?(\?[a-zA-Z+&\$_.-][a-z0-9A-Z;:@\/&%=\{\}+\$_.-]*)?(#[a-zA-Z_.-\/0-9]{0,1}[a-zA-Z0-9+\$_.-:]*)?$/i';
        foreach(func_get_args() as $value)
        {
            if (preg_match($urlregex, $value)) continue;
            return false;
        }
        return true;
    }

    public static function color($data)
    {
        return (bool)preg_match('/^#[\da-fA-F]{6}$/', $data);
    }

    public static function dow($data)
    {
        $dowsArr = array(1, 2, 3, 4, 5, 6, 7);
        foreach(func_get_args() as $value)
        {
            if(is_array($value))        // если передали массив, то проверяем каждое значение из массива
            {
                foreach($value as $val)
                {
                    if(!self::dow($val))
                    {
                        return false;
                    }
                }
            }
            else
            {
                if(!in_array($value, $dowsArr))
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param string $fileName имя файла без полного пути
     * @return bool
     */
    public static function userImageFileName($fileName)
    {
        $fileName = html_entity_decode($fileName);
        // если в имени есть точка или слеш, или имя файла начинается с точки то выходим
        if(preg_match('/((\.\.)+)|(([\/\\\])+)/i', $fileName) > 0 || substr($fileName, 0, 1) == '.')
        {
            return false;
        }

        $pathInfo = pathinfo($fileName);

        $result = true;
        if(!isset($pathInfo['extension']) || !in_array($pathInfo['extension'], Validate::$allowedImagesExtensions))
        {
            $result = false;
        }

        return $result;
    }
    
    
    public static function quote($text)
	{
		$search = array('%','_');
		$replace = array('\%','\_');

		$text = str_replace($search, $replace , $text);
		return $text;
	}

	public static function nohtml($data)
	{
		if (!is_array($data)) return htmlspecialchars($data, ENT_QUOTES);

		foreach ($data as $key => $string)
		{
			if($string !== null)
				$data[$key] = htmlspecialchars($string, ENT_QUOTES);
		}

		return $data;
	}
}

