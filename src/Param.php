<?php

declare(strict_types=1);

namespace Cxx\ParamInject;

use ArrayAccess;
use Roave\BetterReflection\BetterReflection;

/**
 * 参数注入类
 */
abstract class Param implements ArrayAccess
{
    /** @var ParamInject */
    protected static $inject;

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
        $this->{$offset} = $value;
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

    /**
     * @param array $params
     * @return static
     */
    public static function newInstance(array $params = [])
    {
        $instance = new static();
        if (self::$inject) {
            return (self::$inject)->injectParam($instance, $params);
        }
        if (function_exists('app')) {
            /** @var ParamInject */
            $inject = app(ParamInject::class);
        } else {
            $inject = new ParamInject(new BetterReflection());
        }
        self::$inject = $inject;
        return $inject->injectParam($instance, $params);
    }
}
