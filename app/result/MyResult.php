<?php

namespace app\result;
class MyResult
{
    static function myResult($code, $msg, $data = null)
    {
        echo json_encode(['code' => $code, 'msg' => $msg, 'data' => $data, 'timestamp' => time()], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

