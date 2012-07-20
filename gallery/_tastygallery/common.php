<?php

///////////////////////////////
// thumnnail quality
/////////////////

// from 1 to 5. default=4.
// 1 = low, 5 = high
$quality=4;

///////////////////////////////
// thumnnail directory
/////////////////

// default=_thumb. NO LEADING OR TRAILING SLASHES!
$thumbdir='_thumb';

////////////////////////////// end of user-configurable options //////////////////////////////

$ext=array('jpg','jpeg','png','apng','gif');

$t_fn='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$t_dn='http://'.unslash($_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');

function unslash($string){
	while(strpos($string,'//')!==false){
		$string=str_replace('//','/',$string);
	}
	return $string;
}

// json_encode for PHP 4 and early PHP 5 - thanks Steve!
// http://au.php.net/manual/en/function.json-encode.php#82904
if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}