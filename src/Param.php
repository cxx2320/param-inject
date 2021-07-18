<?php

declare(strict_types=1);

namespace Cxx\ParamInject;

use ArrayAccess;

/**
 * 参数注入类
 */
abstract class Param implements ArrayAccess
{
    public function toArray(): array
    {
        return json_decode($this->toJson(), true);
    }

    public function toJson(int $options = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this, $options);
    }

    public function offsetSet($offset, $value)
    {
        if (property_exists($this, $offset)) {
            $this->{$offset} = $value;
        }
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    public function offsetGet($offset)
    {
        return property_exists($this, $offset) ? $this->{$offset} : null;
    }
}
