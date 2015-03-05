<?php
class Zend_Utf8 
{ 
    /** 
    * Escape UTF-8 characters using the given options 
    * 
    * About the write.callback option, it receives the given read.arguments 
    * option plus a unicode integer, and must return a string. 
    * 
    * @link http://noteslog.com/post/escaping-and-unescaping-utf-8-characters-in-php/ 
    * 
    * @param  string $value 
    * @param  array $options 
    *  'escapeControlChars'  => boolean (default: TRUE), 
    *  'escapePrintableASCII' => boolean (default: FALSE), 
    *  'write'                => array( 
    *      'callback'  => callable (default: 'sprintf'), 
    *      'arguments' => array    (default: array('\u%04x')), 
    *  ), 
    *  'extendedUseSurrogate' => boolean (default: true), 
    * 
    * @throws Zend_Utf8_Exception If the code point of any char in $value is 
    *                            not unicode 
    * @return string 
    */ 
    public static function escape($value, array $options = array()) 
    { 
        $options = array_merge(array( 
            'escapeControlChars'  => true, 
            'escapePrintableASCII' => false, 
            'write'                => array( 
                'callback'  => 'sprintf', 
                'arguments' => array('\u%04x'), 
            ), 
            'extendedUseSurrogate' => true, 
        ), $options); 
        if (! self::isCallable($options['write'])) 
        { 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected a valid write (callable, array).'); 
        } 
        if (self::validateFilters($options) && isset($options['filters']['before-write'])) 
        { 
            $value = self::call($options['filters']['before-write'], $value); 
        } 
        
        $result = ""; 
        $length = strlen($value); 
        for($i = 0; $i < $length; $i++) { 
            $ord_var_c = ord($value[$i]); 
            
            switch (true) { 
                case ($ord_var_c < 0x20): 
                    // code points 0x00000000..0x0000001F, mask 0xxxxxxx 
                    $result .= $options['escapeControlChars'] 
                        ? self::call($options['write'], $ord_var_c) 
                        : $value[$i]; 
                break; 
  
                case ($ord_var_c < 0x80): 
                    // code points 0x00000020..0x0000007F, mask 0xxxxxxx 
                    $result .= $options['escapePrintableASCII'] 
                        ? self::call($options['write'], $ord_var_c) 
                        : $value[$i]; 
                break; 
  
                case (($ord_var_c & 0xE0) == 0xC0): 
                    // code points 0x00000080..0x000007FF, mask 110yyyyy 10xxxxxx 
                    $utf8Char = substr($value, $i, 2); $i += 1; 
                    $code = self::utf8CharToCodePoint($utf8Char); 
                    $result .= self::call($options['write'], $code); 
                break; 
  
                case (($ord_var_c & 0xF0) == 0xE0): 
                    // code points 0x00000800..0x0000FFFF, mask 1110zzzz 10yyyyyy 10xxxxxx 
                    $utf8Char = substr($value, $i, 3); $i += 2; 
                    $code = self::utf8CharToCodePoint($utf8Char); 
                    $result .= self::call($options['write'], $code); 
                break; 
  
                case (($ord_var_c & 0xF8) == 0xF0): 
                    // code points 0x00010000..0x0010FFFF, mask 11110www 10zzzzzz 10yyyyyy 10xxxxxx 
                    $utf8Char = substr($value, $i, 4); $i += 3; 
                    if ($options['extendedUseSurrogate']) 
                    { 
                        list($upper, $lower) = self::utf8CharToSurrogatePair($utf8Char); 
                        $result .= self::call($options['write'], $upper); 
                        $result .= self::call($options['write'], $lower); 
                    } 
                    else 
                    { 
                        $code = self::utf8CharToCodePoint($utf8Char); 
                        $result .= self::call($options['write'], $code); 
                    } 
                break; 
  
                default: 
                    //no more cases in unicode, whose range is 0x00000000..0x0010FFFF 
                    require_once 'Exception.php'; 
                    throw new Zend_Utf8_Exception('Expected a valid UTF-8 character.'); 
                break; 
            } 
        } 
  
        return $result; 
    } 
    
    /** 
    * Compute the code point of a given UTF-8 character 
    * 
    * If available, use the multibye string function mb_convert_encoding 
    * TODO reject overlong sequences in $utf8Char 
    * 
    * @link http://noteslog.com/post/escaping-and-unescaping-utf-8-characters-in-php/ 
    * 
    * @param  string $utf8Char 
    * @throws Zend_Utf8_Exception If the code point of $utf8Char is not unicode 
    * @return integer 
    */ 
    public static function utf8CharToCodePoint($utf8Char) 
    { 
        if (function_exists('mb_convert_encoding')) 
        { 
            $utf32Char = mb_convert_encoding($utf8Char, 'UTF-32', 'UTF-8'); 
        } 
        else 
        { 
            $bytes = array('C*'); 
            list(, $utf8Int) = unpack('N', str_repeat(chr(0), 4 - strlen($utf8Char)) . $utf8Char); 
            switch (strlen($utf8Char)) 
            { 
                case 1: 
                    //Code points U+0000..U+007F 
                    //mask  0xxxxxxx (7 bits) 
                    //map to 00000000 00000000 00000000 0xxxxxxx 
                    $bytes[] = 0; 
                    $bytes[] = 0; 
                    $bytes[] = 0; 
                    $bytes[] = $utf8Int; 
                break; 
    
                case 2: 
                    //Code points U+0080..U+07FF 
                    //mask  110yyyyy 10xxxxxx (5 + 6 = 11 bits) 
                    //map to 00000000 00000000 00000yyy yyxxxxxx 
                    $bytes[] = 0; 
                    $bytes[] = 0; 
                    $bytes[] = $utf8Int >> 10 & 0x07; 
                    $bytes[] = $utf8Int >>  2 & 0xC0 | $utf8Int      & 0x3F; 
                break; 
    
                case 3: 
                    //Code points U+0800..U+D7FF and U+E000..U+FFFF 
                    //mask  1110zzzz 10yyyyyy 10xxxxxx (4 + 6 + 6 = 16 bits) 
                    //map to 00000000 00000000 zzzzyyyy yyxxxxxx 
                    $bytes[] = 0; 
                    $bytes[] = 0; 
                    $bytes[] = $utf8Int >> 12 & 0xF0 | $utf8Int >> 10 & 0x0F; 
                    $bytes[] = $utf8Int >>  2 & 0xC0 | $utf8Int      & 0x3F; 
                break; 
                            
                case 4: 
                    //Code points U+10000..U+10FFFF 
                    //mask  11110www 10zzzzzz 10yyyyyy 10xxxxxx (3 + 6 + 6 + 6 = 21 bits) 
                    //map to 00000000 000wwwzz zzzzyyyy yyxxxxxx 
                    $bytes[] = 0; 
                    $bytes[] = $utf8Int >> 22 & 0x1C | $utf8Int >> 20 & 0x03; 
                    $bytes[] = $utf8Int >> 12 & 0xF0 | $utf8Int >> 10 & 0x0F; 
                    $bytes[] = $utf8Int >>  2 & 0xC0 | $utf8Int      & 0x3F; 
                break; 
                
                default: 
                    //no more cases in unicode, whose range is 0x00000000 - 0x0010FFFF 
                    require_once 'Exception.php'; 
                    throw new Zend_Utf8_Exception('Expected a valid UTF-8 character.'); 
                break; 
            } 
            $utf32Char = call_user_func_array('pack', $bytes); 
        } 
        list(, $result) = unpack('N', $utf32Char); //unpack returns an array with base 1 
        if (0xD800 <= $result && $result <= 0xDFFF) 
        { 
            //reserved for UTF-16 surrogates 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected a valid UTF-8 character.'); 
        } 
        if (0xFFFE == $result || 0xFFFF == $result) 
        { 
            //reserved 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected a valid UTF-8 character.'); 
        } 
        
        return $result; 
    } 
    
    /** 
    * Compute the surrogate pair of a given extended UTF-8 character 
    * 
    * @link http://noteslog.com/post/escaping-and-unescaping-utf-8-characters-in-php/ 
    * @link http://en.wikipedia.org/wiki/UTF-16/UCS-2 
    * 
    * @param  string $utf8Char 
    * @throws Zend_Utf8_Exception If the code point of $utf8Char is not extended unicode 
    * @return array 
    */ 
    public static function utf8CharToSurrogatePair($utf8Char) 
    { 
        $codePoint = self::utf8CharToCodePoint($utf8Char); 
        if ($codePoint < 0x10000) 
        { 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected an extended UTF-8 character.'); 
        } 
        $codePoint -= 0x10000; 
        $upperSurrogate = 0xD800 + ($codePoint >> 10); 
        $lowerSurrogate = 0xDC00 + ($codePoint & 0x03FF); 
        $result = array($upperSurrogate, $lowerSurrogate); 
        
        return $result; 
    } 
    
    /** 
    * Unescape UTF-8 characters from a given escape format 
    * 
    * About the read.pattern option 
    * -- no delimiters and no modifiers allowed 
    * -- for back references, your groups start at 3. 
    * About the read.callback option 
    * -- it receives the given read.arguments option plus all the matches 
    * -- it must return a unicode integer. 
    * 
    * @link http://noteslog.com/post/escaping-and-unescaping-utf-8-characters-in-php/ 
    * 
    * @param  string $value 
    * @param  array $options 
    *  'read'                => array( 
    *        'pattern'  => preg    (default: '\\\\u([0-9A-Fa-f]{4})'), 
    *      'callback'  => callable (default: create_function('$all, $code', 'return hexdec($code);')), 
    *      'arguments' => array    (deafult: array()), 
    *  ), 
    *  'extendedUseSurrogate' => boolean (default: TRUE), 
    * 
    * @throws Zend_Utf8_Exception If the code point of any char in $value is 
    *                            not unicode 
    * 
    * @return string 
    */ 
    public static function unescape($value, array $options = array()) 
    { 
        $options = array_merge(array( 
            'read'                => array( 
                'pattern'  => '\\\\u([0-9A-Fa-f]{4})', 
                'callback'  => create_function('$all, $code', 'return hexdec($code);'), 
                'arguments' => array(), 
            ), 
            'extendedUseSurrogate' => true, 
        ), $options); 
        if (! self::isCallable($options['read'])) 
        { 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected a valid read (callable, array).'); 
        } 
        $thereAreFilters = self::validateFilters($options); 
        
        $result = ""; 
        $length = strlen($value); 
        $pattern = '@([\w\W]*?)(' . $options['read']['pattern'] . ')|([\w\W]+)@'; 
        $offset = 0; 
        while (preg_match($pattern, $value, $matches, 0, $offset)) 
        { 
            if (! $matches[2]) 
            { 
                //no more escape patterns 
                $result .= $matches[0]; 
                $offset += strlen($matches[0]); 
            } 
            else 
            { 
                //one more escape pattern 
                $result .= $matches[1]; 
                $offset += strlen($matches[0]); 
                $args = array_splice($matches, 2, count($matches) - 1); 
                $unicode = self::call($options['read'], $args);//                call_user_func($options['integer'], $matches[2]); 
                if ($options['extendedUseSurrogate'] && (0xD800 <= $unicode && $unicode < 0xDC00)) 
                { 
                    $upperSurrogate = $unicode; 
                    preg_match($pattern, $value, $matches, 0, $offset); 
                    if (! $matches[2]) 
                    { 
                        require_once 'Exception.php'; 
                        throw new Zend_Utf8_Exception('Expected an extended UTF-8 character.'); 
                    } 
                    $offset += strlen($matches[0]); 
                    $args = array_splice($matches, 2, count($matches) - 1); 
                    $unicode = self::call($options['read'], $args);//$lowerSurrogate = call_user_func($options['integer'], $matches[2]); 
                    $utf8Char = self::utf8CharFromSurrogatePair(array($upperSurrogate, $unicode)); 
                } 
                else 
                { 
                    $utf8Char = self::utf8CharFromCodePoint($unicode); 
                } 
                $result .= $utf8Char; 
            } 
        } 
        if ($thereAreFilters && isset($options['filters']['after-read'])) 
        { 
            $result = self::call($options['filters']['after-read'], $result); 
        } 
        
        return $result; 
    } 
    
    /** 
    * Compute the UTF-8 character of a given code point 
    * 
    * If available, use the multibye string function mb_convert_encoding 
    * 
    * @link http://noteslog.com/post/escaping-and-unescaping-utf-8-characters-in-php/ 
    * 
    * @param  integer $codePoint 
    * @throws Zend_Utf8_Exception if the code point is not unicode 
    * @return string 
    */ 
    public static function utf8CharFromCodePoint($codePoint) 
    { 
        if (0xD800 <= $codePoint && $codePoint <= 0xDFFF) 
        { 
            //reserved for UTF-16 surrogates 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected a valid code point.'); 
        } 
        if (0xFFFE == $codePoint || 0xFFFF == $codePoint) 
        { 
            //reserved 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected a valid code point.'); 
        } 
        
        if (function_exists('mb_convert_encoding')) 
        { 
            $utf32Char = pack('N', $codePoint); 
            $result = mb_convert_encoding($utf32Char, 'UTF-8', 'UTF-32'); 
        } 
        else 
        { 
            $bytes = array('C*'); 
            switch (true) 
            { 
                case ($codePoint < 0x80): 
                    //Code points U+0000..U+007F 
                    //mask    0xxxxxxx (7 bits) 
                    //map from xxxxxxx 
                    $bytes[] = $codePoint; 
                break; 
                
                case ($codePoint < 0x800): 
                    //Code points U+0080..U+07FF 
                    //mask    110yyyyy 10xxxxxx (5 + 6 = 11 bits) 
                    //map from yyy yyxxxxxx 
                    $bytes[] = 0xC0 | $codePoint >> 6; 
                    $bytes[] = 0x80 | $codePoint      & 0x3F; 
                break; 
                
                case ($codePoint < 0x10000): 
                    //Code points U+0800..U+D7FF and U+E000..U+FFFF 
                    //mask    1110zzzz 10yyyyyy 10xxxxxx (4 + 6 + 6 = 16 bits) 
                    //map from zzzzyyyy yyxxxxxx 
                    $bytes[] = 0xE0 | $codePoint >> 12; 
                    $bytes[] = 0x80 | $codePoint >> 6  & 0x3F; 
                    $bytes[] = 0x80 | $codePoint      & 0x3F; 
                break; 
                
                case ($codePoint < 0x110000): 
                    //Code points U+10000..U+10FFFF 
                    //mask    11110www 10zzzzzz 10yyyyyy 10xxxxxx (3 + 6 + 6 + 6 = 21 bits) 
                    //map from wwwzz zzzzyyyy yyxxxxxx 
                    $bytes[] = 0xF0 | $codePoint >> 18; 
                    $bytes[] = 0x80 | $codePoint >> 12 & 0x3F; 
                    $bytes[] = 0x80 | $codePoint >> 6  & 0x3F; 
                    $bytes[] = 0x80 | $codePoint      & 0x3F; 
                break; 
                
                default: 
                    require_once 'Exception.php'; 
                    throw new Zend_Utf8_Exception('Expected a valid code point.'); 
                break; 
            } 
            $result = call_user_func_array('pack', $bytes); 
        } 
        return $result; 
    } 
    
    /** 
    * Compute the extended UTF-8 character of a given surrogate pair 
    * 
    * @link  http://noteslog.com/post/escaping-and-unescaping-utf-8-characters-in-php/ 
    * @link http://en.wikipedia.org/wiki/UTF-16/UCS-2 
    * 
    * @param array $surrogatePair 
    * @throws Zend_Utf8_Exception If the surrogate pair is not extended unicode 
    * @return string 
    */ 
    public static function utf8CharFromSurrogatePair($surrogatePair) 
    { 
        list($upperSurrogate, $lowerSurrogate) = $surrogatePair; 
        if (! (0xD800 <= $upperSurrogate && $upperSurrogate < 0xDC00)) 
        { 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected an extended UTF-8 character.'); 
        } 
        if (! (0xDC00 <= $lowerSurrogate && $lowerSurrogate < 0xE000)) 
        { 
            require_once 'Exception.php'; 
            throw new Zend_Utf8_Exception('Expected an extended UTF-8 character.'); 
        } 
        $codePoint = ($upperSurrogate & 0x03FF) << 10 | ($lowerSurrogate & 0x03FF); 
        $codePoint += 0x10000; 
        $result = self::utf8CharFromCodePoint($codePoint); 
        
        return $result; 
    } 
    
    /** 
    * A little calling interface: validation 
    * 
    * @param  array  $handler 
    * @return boolean 
    */ 
    private static function isCallable($handler) 
    { 
        $result = is_callable($handler['callback']) && is_array($handler['arguments']); 
        return $result; 
    } 
    
    /** 
    * A little calling interface: call 
    * 
    * @param  array  $handler 
    * @param  mixed  $args 
    * @return mixed 
    */ 
    private static function call($handler, $args) 
    { 
        $args = array_merge($handler['arguments'], is_array($args) ? $args : array($args)); 
        $result = call_user_func_array($handler['callback'], $args); 
        return $result; 
    } 
    
    /** 
    * Validate filters. If there are filters return true, else false 
    * 
    * @param array $options 
    * @throws Zend_Utf8_Exception If there are malformed filters 
    * @return boolean 
    */ 
    protected static function validateFilters($options) 
    { 
        if (isset($options['filters'])) 
        { 
            if (! is_array($options['filters'])) 
            { 
                require_once 'Exception.php'; 
                throw new Zend_Utf8_Exception('Expected valid filters.'); 
            } 
            foreach ($options['filters'] as $key => $value) 
            { 
                if (! self::isCallable($value)) 
                { 
                    require_once 'Exception.php'; 
                    throw new Zend_Utf8_Exception("Expected a valid $key filter."); 
                } 
            } 
            return true; 
        } 
        return false; 
    } 
    
} 
?>