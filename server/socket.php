<?php
/**
 * Socket统计shadowsocks的流量
 */
error_reporting(E_ALL);
//设置无限请求超时时间
set_time_limit(0);
$CONFIG = include 'config.php';
require 'vendor/autoload.php';

//创建socket
if(($socket = socket_create(AF_UNIX,SOCK_DGRAM,0)) < 0) {
    echo "socket_create() error:".socket_strerror($socket)."\n";
    exit();
}
//给套接字绑定名字
unlink('/tmp/client.sock');
$CLIENT_ADDRESS = '/tmp/client.sock';
if(($sock = socket_bind($socket, $CLIENT_ADDRESS))<0) {
	echo "socket_bind() error:".socket_strerror($sock)."\n";
    exit();
}

//连接socket
$SERVER_ADDRESS = '/var/run/shadowsocks-manager.sock';
if(($sock = socket_connect($socket, $SERVER_ADDRESS)) < 0){
    echo "socket_connect() error:".socket_strerror($sock)."\n";
    exit();
}

// $in = "ping";
// $out = '';

// //写数据到socket缓存
// if(($sock = socket_write($socket, $in, strlen($in)))<0) {
//     echo "socket_write() error:".socket_strerror($sock)."\n";
//     exit();
// }
// echo "send:$in \n";

//Redis
$redis = new Predis\Client([
        'host' => $CONFIG['redis']['HOST'],
        'port' => $CONFIG['redis']['PORT'],
        'password'  =>  $CONFIG['redis']['AUTH']
    ]);
//接收信息
while (true){
    while($out = socket_read($socket, 2048)) {
        if(!empty($out)){
          preg_match('/(?:\{)(.*)(?:\})/i', $out, $transfer);
        if(is_array($transfer)){
            $transfer = json_decode($transfer[0],true);
            if(!empty($transfer) && is_array($transfer)){
                foreach ($transfer as $tran_port => $tran_value) {
                    $redis->hincrby($tran_port, date('Y-m-d'), $tran_value);
                }
            }
        }
        echo date('Y-m-d H:i:s')."$out \n";  
        }
	}
	usleep(1000);
}

socket_close($socket);