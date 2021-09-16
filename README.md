# Param-Inject

使用依赖注入提供请求参数注入功能，可更直观的处理客户端数据

# Install

Thinkphp >= 6.0

Laravel >= 5.0

`composer require cxx/param-inject`

# Start

1. 添加服务(支持tp和laravel)

   服务使用方式请查看官方文档([Thinkphp](https://www.kancloud.cn/manual/thinkphp6_0/1037490), [Laravel](https://learnku.com/docs/laravel/8.x/providers/9362))

   Thinkphp：`Cxx\ParamInject\ThinkParamService`

   Laravel：`Cxx\ParamInject\LaravelParamService`

2. 创建参数类（命名空间随意，只要能被加载）

   ```php
   use Cxx\ParamInject\Param;
   
   /**
    * 参数类(所有参数类都需要继承 Cxx\ParamInject\Param)
    */
   class Test extends Param
   {
       // 属性访问性必须是 public
       /**
        * @var int
        */
       public $page = 1;
   
       // 注释规则必须要有 @var 后面跟属性类型
       /**
        * @var int
        */
       public $limit = 10;
       
       // 也可以写在行内
       /** @var int */
       public $limit = 10;
       
       // 不带默认值，均为 null
       /** @var int */
       public $limit;
   }
   ```

3. 使用

   控制器中

   ```php
   // 注意方法参数类型
   public function index(Test $test)
   {
       dd($test);
   }
   
   ```

   在路由闭包中也类似

    ```php
   // 注意匿名函数参数类型
   Route::post('/test', function (Test $test) {
       dd($test);
   });
    ```

   使用方式在`Thinkphp`、`Laravel`中一致

# 类型注释集合

> 注意：有时在类型转换时会发生和预期的结果不一致的情况，详见[类型转换的判别](https://www.php.net/manual/zh/language.types.type-juggling.php)

## 基础类型

| 示例                      | 备注                     |
| ------------------------- | ------------------------ |
| @var int 或 @var integer  | 整形                     |
| @var string               | 字符串                   |
| @var bool 或 @var boolean | 布尔类型                 |
| @var float 或 @var double | 浮点型                   |
| @var mixed                | 此类型不会对数据进行转换 |

以上类型支持数组形式 如`@var int[]`  `@var int[][]` 

## 复合类型

| 示例        | 备注                                              |
| ----------- | ------------------------------------------------- |
| @var Test   | 对象类型，Test是继承`Cxx\ParamInject\Param`的对象 |
| @var Test[] | 对象数组类型                                      |

对象类型里面的属性可以是基础类型还可以是复合类型

