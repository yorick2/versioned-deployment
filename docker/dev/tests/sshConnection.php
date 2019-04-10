<?php

$host ='example.com';
$port = 22;
$user = 'test';
$password = 'password1';

if(!($sshConnection = @ssh2_connect($host, $port))){
    echo "Cannot connect to server\n<br/>";
    exit;
}
echo "-> ssh start connection test passed\n<br/>";
if (!@ssh2_auth_password($sshConnection,$user,$password)) {
    echo "Authentication rejected by server\n<br/>";
    exit;
}
echo "-> ssh authentication test passed\n<br/>";
if (!($stream = ssh2_exec($sshConnection, "echo '-> ssh command test passed <br/>';"))) {
    echo "ssh command failed\n";
    exit;
}
stream_set_blocking($stream, true);
$data = "";
while ($buf = fread($stream, 4096)) {
    $data .= $buf;
}
fclose($stream);
echo $data;

echo "connection success\n";
