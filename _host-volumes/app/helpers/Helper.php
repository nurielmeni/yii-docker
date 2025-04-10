<?php

namespace app\helpers;

class Helper
{
    /*
      chr(45): "-"
      chr(123): "{"
      chr(125): "}"
     */

    public static function emptyGuid()
    {
        return chr(123) . "00000000-0000-0000-0000-000000000000" . chr(125);
    }

    public static function newGuid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((float) microtime() * 10000); /* optional for php 4.2.0 and up. */
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = chr(123)
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125);
            return $uuid;
        }
    }

    public static function xml2array($xml)
    {
        $arr = [];
        foreach ($xml->children() as $r) {
            if (count($r->children()) == 0) {
                $arr[$r->getName()] = strval($r);
            } else {
                $arr[$r->getName()][] = self::xml2array($r);
            }
        }
        return $arr;
    }

    public static function getObjValue($obj, $key, $default = '')
    {
        return isset($obj->$key) ? htmlspecialchars($obj->$key) : $default;
    }

    public static function getArrValue($arr, $key, $default = '')
    {
        return key_exists($key, $arr) && is_string($arr[$key]) ? htmlspecialchars($arr[$key]) : $default;
    }

    public static function getExtendedProperty($extendedProperties, $name, $default = '')
    {
        foreach ($extendedProperties as $extendedProperty) {
            //$extendedProperty = is_array($extendedProperty->ExtendedProperty) ? $extendedProperty->ExtendedProperty : $extendedProperty;
            $extendedProperty = !is_array($extendedProperty->ExtendedProperty) ? [$extendedProperty->ExtendedProperty] : $extendedProperty->ExtendedProperty;
            foreach ($extendedProperty as $property) {
                if (isset($property->PropertyName) && $property->PropertyName === $name) {
                    return isset($property->Value) ? $property->Value : $default;
                }
            }
        }
        return $default;
    }
}
