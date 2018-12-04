<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SshConnection extends Model
{
    protected $guarded = [];

    const AUTHENTICATION_FAILED_ERROR_MESSAGE = 'ssh authentication failed';
    const CONNECTION_FAILED_ERROR_MESSAGE = 'ssh connection failed';
    const FAILED_TO_SHELL = 'ssh authentication failed';

    // SSH Server Fingerprint
    private $ssh_server_fp = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    // SSH Username
    // SSH Public Key File
    private $ssh_auth_pub = '/home/www-data/.ssh/id_rsa.pub';
    // SSH Private Key File
    private $ssh_auth_priv = '/home/www-data/.ssh/id_rsa';
    // SSH Private Key Passphrase (null == no passphrase)
    private $ssh_auth_pass;
    // SSH Connection
    protected $passwordFile = '/home/www-data/.ssh/647422esed';
    protected $sshConnection;


    public function connect()
    {
        return $this->connectWithKey();
    }

    public function connectWithKey()
    {
        if (!($this->sshConnection = @ssh2_connect($this->getAttribute('deploy_host'), $this->getAttribute('deploy_port')))) {
            return ['success'=>0, 'message'=>'Cannot connect to server'];
        }
//        $fingerprint = ssh2_fingerprint($this->sshConnection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
//        if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
//            throw new Exception('Unable to verify server identity!');
//        }
        if (!@ssh2_auth_pubkey_file($this->sshConnection, $this->getAttribute('deploy_user'), $this->ssh_auth_pub, $this->ssh_auth_priv, $this->ssh_auth_pass)) {
            return ['success'=>0, 'message'=>'Authentication rejected by server'];
        }
    }

    public function connectWithPassword()
    {
        if (!($this->sshConnection=@ssh2_connect($this->getAttribute('deploy_host'), $this->getAttribute('deploy_port')))) {
            return ['success'=>0, 'message'=>'Cannot connect to server'];
        }
        if (!@ssh2_auth_password($this->sshConnection,$this->getAttribute('deploy_user'),$this->getAttribute('deploy_password'))) {
            return ['success'=>0, 'message'=>'Authentication rejected by server'];
        }
    }

    public function addSshKey()
   {
        $port = $this->getAttribute('deploy_port');
        $user = $this->getAttribute('deploy_user');
        $host = $this->getAttribute('deploy_host');

        $file = fopen($this->passwordFile, "w");
        fwrite($file, $this->getAttribute('deploy_password'));
        fclose($file);

        $cmd = "/usr/bin/sshpass -f $this->passwordFile /usr/bin/ssh-copy-id -i $this->ssh_auth_priv -p $port $user@$host";
        $res = exec($cmd);
        unlink($this->passwordFile);
        return $this->connectWithKey()['success'];
#       sshpass -f /home/www-data/.ssh/647422esed ssh-copy-id -i /home/www-data/.ssh/id_rsa '-p 22 test@172.21.0.2'
//        ssh-copy-id -p 22 -i /home/www-data/.ssh/id_rsa test@172.21.0.2
#      sshpass -f /home/www-data/.ssh/647422esed ssh-copy-id -i /home/www-data/.ssh/id_rsa -p 22 test@172.21.0.2
    }

    public function execute($cmd)
    {
        if (!($stream = ssh2_exec($this->sshConnection, $cmd))) {
            return ['success'=>0, 'message'=>'SSH command failed'];
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return $data;
    }

    public function disconnect() {
        $this->exec('echo "EXITING" && exit;');
        $this->sshConnection = null;
    }

//    public function __destruct() {
//        $this->disconnect();
//    }

}
