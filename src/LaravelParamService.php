<?php

declare(strict_types=1);

namespace Cxx\ParamInject;

use Illuminate\Http\Request;
use Cxx\ParamInject\ParamInject;
use Illuminate\Support\ServiceProvider;
use Cxx\ParamInject\Param as ParamAbstract;

/**
 * Laravel应用服务类
 */
class LaravelParamService extends ServiceProvider
{
    /**
     * 启动应用服务。
     *
     * @return void
     */
    public function boot(ParamInject $inject)
    {
        $this->app->resolving(function ($object, $app) use ($inject) {
            if (!($object instanceof ParamAbstract)) {
                return;
            }
            /** @var Request */
            $request = $app->get('request');
            $param = $request->all();
            $inject->injectParam($object, $param);
        });
    }
}
