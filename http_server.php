$socket = stream_socket_server("tcp://0.0.0.0:8080", $errno, $errstr);
if (!$socket) {
          echo "$errstr ($errno)<br />\n";
} else {
     
          echo '正在监听';
          while ($conn = stream_socket_accept($socket)) {
              //$sock_data = fread($conn, 1024);
              //echo $sock_data;
              $res="HTTP/1.1 200 ok\r\nAccept-Ranges: bytes\r\ncontent-type: text/html; charset=utf-8\r\n\r\nThe local time is ".date('Y-m-d H:i:s')."\r\n";
              fwrite($conn,$res);
                  fclose($conn);
              }
        }
}
