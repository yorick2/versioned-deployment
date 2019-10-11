<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class SshConnection extends Model implements SshConnectionInterface
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

    /**
     * @return App\DeploymentMessageInterface
     */
    public function connect(): DeploymentMessageInterface
    {
        return $this->connectWithKey();
    }

    /**
     * @return App\DeploymentMessageInterface
     */
    protected function connectWithKey(): DeploymentMessageInterface
    {
        if ($message = $this->makeSshConnection()['success'] == 0) {
            return App::make(
                'App\DeploymentMessageInterface',
                $message
            );
        }
//        if($message = $this->checkServerFingerprint()){
//            return App::make(
//                  'App\DeploymentMessageInterface',
//                  $message
//            );
//        }
        if ($message = $this->authenticateWithPublicKey()['success'] == 0) {
            return App::make(
                'App\DeploymentMessageInterface',
                $message
            );
        }
        return App::make(
            'App\DeploymentMessageInterface',
            [
                'name'=>'test ssh connection',
                'success' => 1,
                'message'=>'test ssh connection'
            ]
        );
    }

    /**
     * @return App\DeploymentMessageInterface
     */
    protected function connectWithPassword(): DeploymentMessageInterface
    {
        if ($message = $this->makeSshConnection()['success'] == 0) {
            return App::make(
                'App\DeploymentMessageInterface',
                $message
            );
        }
//        if($message = $this->checkServerFingerprint()){
//            return App::make(
        //            'App\DeploymentMessageInterface',
        //            $message
//              );
//        }
        if ($message = $this->authenticateWithPassword()['success'] == 0) {
            return App::make(
                'App\DeploymentMessageInterface',
                $message
            );
        }
        return App::make(
            'App\DeploymentMessageInterface',
            [
                'name'=>'test ssh connection',
                'success'=>1,
                'message'=>'test ssh connection'
            ]
        );
    }

    /**
     * @return array
     */
    protected function makeSshConnection(): array
    {
        if (!($this->sshConnection=@ssh2_connect($this->getAttribute('deploy_host'), $this->getAttribute('deploy_port')))) {
            return [
                    'name'=>'connect with password',
                    'success' => 0,
                    'message'=>'Cannot connect to server'
                ];
        }
        return ['success'=>1];
    }

    /**
     * @return array
     */
    protected function authenticateWithPublicKey(): array
    {
        if (!@ssh2_auth_pubkey_file($this->sshConnection, $this->getAttribute('deploy_user'), $this->ssh_auth_pub, $this->ssh_auth_priv, $this->ssh_auth_pass)) {
            return [
                    'name'=>'test ssh connection',
                    'success' => 0,
                    'message'=>'Authentication with ssh key rejected by server'
                ];
        }
        return ['success'=>1];
    }

//    protected function authenticateWithPassword(){
//        if (!@ssh2_auth_password($this->sshConnection,$this->getAttribute('deploy_user'),$this->getAttribute('deploy_password'))) {
//            return [
//                    'name'=>'connect with password',
//                    'success'=>0,
//                    'message'=>'Authentication rejected by server'
//                ];
//        }
//    }

    /**
     * @return array
     */
    protected function checkServerFingerprint(): array
    {
        $fingerprint = ssh2_fingerprint($this->sshConnection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
        if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
            return [
                    'name'=>'connect with password',
                    'success'=>0,
                    'message'=>'Unable to verify server identity!'
                ];
        }
        return ['success'=>1];
    }


    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        $string = file_get_contents($this->getPublicKeyLocation());
        if ($string) {
            return $string;
        }
        return '';
    }

    /**
     * @return string
     */
    private function getPublicKeyLocation(): string
    {
        return $this->ssh_auth_pub;
    }

    /**
     * @param string $cmd
     * @return App\DeploymentMessageInterface
     */
    public function execute($cmd): DeploymentMessageInterface
    {
        if (!($stream = ssh2_exec($this->sshConnection, $cmd))) {
            return App::make(
                'App\DeploymentMessageInterface',
                [
                    'name'=>'ssh connection execute',
                    'success'=>0,
                    'message'=>'SSH command failed'
                ]
            );
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return App::make(
            'App\DeploymentMessageInterface',
            [
                'name'=>'ssh connection execute',
                'success'=>1,
                'message'=>$data
            ]
        );
    }

    public function disconnect(): void
    {
        $this->execute('echo "EXITING" && exit;');
        $this->sshConnection = null;
    }
}
