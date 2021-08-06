<?php

declare(strict_types=1);

namespace Cxx\ParamInject;

use think\Service;
use think\Container;
use Cxx\ParamInject\ParamInject;
use Cxx\ParamInject\Param as ParamAbstract;

/**
 * Think应用服务类
 */
class ThinkParamService extends Service
{
    /**
     * 启动应用服务。
     *
     * @return void
     */
    public function boot(ParamInject $inject)
    {
        Container::getInstance()->resolving(function ($instance, $container) use ($inject) {
            if (!($instance instanceof ParamAbstract)) {
                return;
            }
            /** @var Request */
            $request = $container->get('request');
            $param = $request->param();
            $inject->injectParam($instance, $param);
        });
    }
}
