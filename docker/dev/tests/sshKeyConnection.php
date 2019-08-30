<?php

$host ='example.com';
$port = 22;
$user = 'test';
$ssh_auth_public = '/home/www-data/.ssh/id_rsa.pub';
$ssh_auth_private = '/home/www-data/.ssh/id_rsa';

$str = <<<HTML
attempting to connect using:<br/>
host: $host<br/>
port: $port<br/>
user: $user<br/>
private key: $ssh_auth_private<br/>
public key: $ssh_auth_public<br/>
<br/><br/>
HTML;
echo $str;


if(!($sshConnection = @ssh2_connect($host, $port))){
    echo "Cannot connect to server\n<br/>";
    exit;
}
echo "-> ssh start connection test passed\n<br/>";
if (!@ssh2_auth_pubkey_file($sshConnection, $user, $ssh_auth_public, $ssh_auth_private, $password)) {
    echo "Authentication rejected by server\n<br/>";
    exit;
}
echo "-> ssh key authentication test passed\n<br/>";
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
