<?php
namespace IW\ZipkinPhpHttp\Model;

use InvalidArgumentException;

abstract class Model
{
    public function __set(string $name, $value) {
        throw new InvalidArgumentException('Cannot setup public property: ' . $name);
    }

    public function toArray(array $array=null): array {
        $array = $array ?? array_filter(get_object_vars($this));

        return array_map(function ($value) {
            if ($value instanceof Model) {
                return $value->toArray();
            } elseif (is_array($value)) {
                return $this->toArray($value);
            } else {
                return $value;
            }
        }, $array);
    }
}
