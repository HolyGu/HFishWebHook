<?php
function request_by_curl($remote_server, $post_string) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$content = file_get_contents('php://input');
$post    = json_decode($content, true);
$project = $post['project'];
$type = $post['type'];
$agent = $post['agent'];
$ip = $post['ip'];
$info = $post['info'];
$time = $post['time'];

if($project){
    $webhook = "https://oapi.dingtalk.com/robot/send?access_token=修改为自己机器人的Token";
    $message="蜜罐来攻击啦~"."\r\n告警项目：".$project."\r\n告警类型：".$type."\r\n告警来源：".$agent."\r\n攻击地址：".$ip."\r\n攻击载荷：".$info."\r\n攻击时间：".$time."\r\n";
    $data = array ('msgtype' => 'text','text' => array ('content' => $message),'at' => array ('isAtAll' => true));
    $data_string = json_encode($data);
    $result = request_by_curl($webhook, $data_string);
}


//echo $result;
?>
