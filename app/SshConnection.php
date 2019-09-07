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
    protected $sshConnection;


    public function connect()
    {
        return $this->connectWithKey();
    }

    protected function connectWithKey()
    {
        if (!($this->sshConnection = @ssh2_connect($this->getAttribute('deploy_host'), $this->getAttribute('deploy_port')))) {
            return ['name'=>'test ssh connection', 'success'=>0, 'message'=>'Cannot connect to server'];
        }
//        $fingerprint = ssh2_fingerprint($this->sshConnection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
//        if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
//            throw new Exception('Unable to verify server identity!');
//        }
        if (!@ssh2_auth_pubkey_file($this->sshConnection, $this->getAttribute('deploy_user'), $this->ssh_auth_pub, $this->ssh_auth_priv, $this->ssh_auth_pass)) {
            return new DeploymentMessage(['name'=>'test ssh connection', 'success'=>0, 'message'=>'Authentication with ssh key rejected by server']);
        }
        return new DeploymentMessage(['name'=>'test ssh connection', 'success'=>1, 'message'=>'test ssh connection']);

    }

    protected function connectWithPassword()
    {
        if (!($this->sshConnection=@ssh2_connect($this->getAttribute('deploy_host'), $this->getAttribute('deploy_port')))) {
            return new DeploymentMessage(['name'=>'connect with password', 'success'=>0, 'message'=>'Cannot connect to server']);
        }
        if (!@ssh2_auth_password($this->sshConnection,$this->getAttribute('deploy_user'),$this->getAttribute('deploy_password'))) {
            return new DeploymentMessage(['name'=>'connect with password', 'success'=>0, 'message'=>'Authentication rejected by server']);
        }
    }

    /**
     * @return string
     */
    public function getPublicKey(){
        $string = file_get_contents($this->getPublicKeyLocation());
        if($string){
            return $string;
        }
        return '';
    }

    /**
     * @return string
     */
    private function getPublicKeyLocation(){
        return $this->ssh_auth_pub;
    }

    /**
     * @param string $cmd
     * @return DeploymentMessage
     */
    public function execute($cmd)
    {
        if (!($stream = ssh2_exec($this->sshConnection, $cmd))) {
            return new DeploymentMessage(['success'=>0, 'message'=>'SSH command failed']);
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return new DeploymentMessage(['success'=>1, 'message'=>$data]);
    }

    public function disconnect() {
        $this->execute('echo "EXITING" && exit;');
        $this->sshConnection = null;
    }

}
