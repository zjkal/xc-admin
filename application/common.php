<?php
// 应用公共文件

/**
 * 拼接Ajax返回的JSON字符串
 * @param int $code 状态
 * @param array|string $data 数据
 * @param string $msg 消息
 * @return \think\response\Json
 */
function msg($code, $data, $msg)
{
    return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
}