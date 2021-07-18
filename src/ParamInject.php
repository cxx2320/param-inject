<?php

declare(strict_types=1);

namespace Cxx\ParamInject;

use ReflectionProperty;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Object_;
use Roave\BetterReflection\BetterReflection;
use Cxx\ParamInject\Param as ParamAbstract;

class ParamInject
{
    /**
     * 注入参数
     * @param ParamAbstract $instance
     * @param array $param
     */
    public function injectParam($instance, $param)
    {
        $reflection = (new BetterReflection())
            ->classReflector()
            ->reflect(get_class($instance));
        $propertyList = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($propertyList as $property) {
            if (!isset($param[$property->getName()])) {
                continue;
            }
            $types = $property->getDocBlockTypes();
            $value = $param[$property->getName()];
            $this->typeCast($types[0] ?? '', $value, $property->getDefaultValue());
            $property->setValue($instance, $value);
        }
        return $instance;
    }

    /**
     * 类型转换
     * @param \phpDocumentor\Reflection\Type $typeClass
     * @param mixed &$value
     * @param mixed $defaultValue
     */
    public function typeCast($typeClass, &$value, $defaultValue): bool
    {
        $type = $typeClass->__toString();
        $scalarMap = [
            'int' => 'int',
            'integer' => 'int',
            'float' => 'float',
            'double' => 'float',
            'string' => 'string',
            'boolean' => 'boolean',
            'bool' => 'boolean'
        ];
        // 标量
        if (isset($scalarMap[$type]) && settype($value, $scalarMap[$type])) {
            return true;
        }

        // 数组
        if ($typeClass instanceof Array_) {
            foreach ($value as $key => $item) {
                $this->typeCast($typeClass->getValueType(), $item, $defaultValue);
                $value[$key] = $item;
            }
            return true;
        }

        // 对象
        if ($typeClass instanceof Object_) {
            $className = $typeClass->getFqsen()->__toString();
            if (!class_exists($className)) {
                throw new \Exception('Class Not Found: ' . $className);
            }
            $instance = new $className;
            if ($instance instanceof ParamAbstract) {
                $value = $this->injectParam($instance, $value);
            } else {
                $value = $instance;
            }
            return true;
        }

        $value = $defaultValue;
        return true;
    }
}
