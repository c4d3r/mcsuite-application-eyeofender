<?php
/**
 * Author: Maxim
 * Date: 17/06/2014
 * Time: 21:14
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ApiController extends Controller
{
    const tblPlayer = "player";

    public function fetchPlayerAtion()
    {
        $user = $this->getUser();
        if(!$user)
            return json_encode(array("success" => false, "message" => "You are not allowed to access this without being logged in."));

        $mysql = $this->container->getParameter('mysql');

        $pdo = new \PDO(sprintf('mysql:dbname=%s;host=%s', $mysql['database'], $mysql['host']), $mysql['username'], $mysql['password']);
        $stmt = $pdo->prepare("SELECT uuid, display_name, name, ender_points, highest_killstreak FROM {self::tblPlayer} WHERE uuid = :uuid;");
        $stmt->execute(array(":uuid" => $user->getMcUuid()));

        if(!($stmt->rowCount() > 0))
            return json_encode(array("success" => false, "message" => "Could not find a record for uuid: " . $user->getMcUuid()));

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode($result);
    }
} 