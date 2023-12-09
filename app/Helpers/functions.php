<?php
if (! function_exists('objectsAttributeToArray')) {
    function objectsAttributeToArray(array $objectArray, $attribute)
    {
        $attributeArray = [];
        foreach($objectArray as $object) {
            $attributeArray[] = $object->$attribute;
        }
        return $attributeArray;
    }
}