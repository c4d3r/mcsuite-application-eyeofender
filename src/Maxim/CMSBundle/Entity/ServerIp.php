<?php
/**
 * An MCSuite product - Sixyce studios
 * File: ServerIp.php
 * User: Maxim
 * Date: 15/02/13
 * Time: 16:58
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\News
 *
 * @ORM\Table(name="server_ip")
 * @ORM\Entity
 */
class ServerIp {

    protected $id;

    protected $host;

    protected $ip;

    protected $server;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Section $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Section
     */
    public function getServer()
    {
        return $this->server;
    }/**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }/**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }




}