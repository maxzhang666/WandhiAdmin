<?php

namespace app\admin\middleware;

use app\admin\service\SystemLogService;
use app\common\extension\CommonTool;

/**
 * 系统操作日志中间件
 * Class SystemLog
 * @package app\admin\middleware
 */
class SystemLog
{

    public function handle($request, \Closure $next)
    {
        if ($request->isAjax()) {
            $method = strtolower($request->method());
            if (in_array($method, ['post', 'put', 'delete'])) {
                $url = $request->url();
                $ip = CommonTool::getRealIp();
                $params = $request->param();
                if (isset($params['s'])) {
                    unset($params['s']);
                }
                $data = [
                    'admin_id'    => session('admin.id'),
                    'url'         => $url,
                    'method'      => $method,
                    'ip'          => $ip,
                    'content'     => json_encode($params, JSON_UNESCAPED_UNICODE),
                    'useragent'   => $_SERVER['HTTP_USER_AGENT'],
                    'create_time' => time(),
                ];
                SystemLogService::instance()->save($data);
            }
        }
        return $next($request);
    }

}