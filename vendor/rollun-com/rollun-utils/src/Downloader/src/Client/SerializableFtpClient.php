<?php


namespace rollun\Downloader\Client;


use FtpClient\FtpClient;

class SerializableFtpClient extends FtpClient implements \Serializable
{

    protected $config = [];

    /**
     * SerializableFtpClient constructor.
     * @param $host
     * @param $login
     * @param $password
     * @throws \FtpClient\FtpException
     */
    public function __construct($host, $login, $password)
    {
        parent::__construct(null);
        $this->connect($host);
        $this->login($login, $password);
        $this->config = [
            "host" => $host,
            "login" => $login,
            "password" => $password
        ];
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize($this->config);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     * @throws \FtpClient\FtpException
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->__construct($data->host, $data->login, $data->password);
    }
}