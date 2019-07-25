<?php

$curChildPro = 0;
$maxChildPro = 5;  // 同一时刻最多 5 个进程
$index  = 0;
$ppid = posix_getpid();
$socket = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
while ($index<20) {
        $index ++;
        $pid =  pcntl_fork();//在此处代码会裂开两部分，一个父进程，一个子进程，可以共享$index变量
        if ($pid == -1) {
                //fork失败

        }elseif ($pid > 0) {
            $curChildPro++;
                        //父进程会得到子进程号$pid，所以这里是父进程执行的逻辑
            //echo "-------- current process\e[1;31m" . $curChildPro . "\e[0m--------.\r\n";
            //echo "\e[1;31m我是父进程{$index},我的进程id是{$ppid}.我的子进程id{$pid}\e[0m".PHP_EOL;
           cli_set_process_title("我是父进程{$index},我的进程id是{$ppid}.我的子进程id{$pid}");
            if ($curChildPro >= $maxChildPro) {
                 pcntl_wait($status);
                 $curChildPro--;
            }
        }elseif($pid==0){
            $cpid = posix_getpid();

            //echo "我是{$ppid}的子进程,我的进程{$index}id是{$cpid}.".PHP_EOL;
            cli_set_process_title("我是{$ppid}的子进程,我的进程id是{$cpid}.");

                if (!$socket) {
                   sleep(1);
                   echo "$errstr ($errno)<br />\n";

                 
                } else {
                      echo '正在监听';
                      while ($conn = stream_socket_accept($socket)) {

                        $res="HTTP/1.1 200 ok\r\nAccept-Ranges: bytes\r\ncontent-type: text/html; charset=utf-8\r\n\r\nThe local time is ".date('Y-m-d H:i:s')."\r\n";
                        fwrite($conn,$res);
                        echo $index.'<br>';
                                fclose($conn);
                        }
                }
                 exit(); // 需要退出，避免产生僵尸进程
        }
}
~  
