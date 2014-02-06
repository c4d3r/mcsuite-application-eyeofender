<?php
/**
 * Author: Maxim
 * Date: 06/02/14
 * Time: 11:04
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \PDO;

class AdminController extends Controller
{
    const QUERY_RESULTSET_THREAD_POSTCOUNT  = "SELECT t.id as thread_id, COUNT(p.id) as post_count
                                                FROM mcsf_thread t
                                                LEFT JOIN mcsf_post p
                                                  ON p.thread_id = t.id
                                                GROUP BY t.id;";


    const QUERY_RESULTSET_THREAD_LASTPOST  = "SELECT a.thread_id as thread_id, a.last_post_id as last_post_id
                                                FROM (
                                                        SELECT t.id as thread_id, p.id as last_post_id
                                                        FROM mcsf_thread t
                                                        LEFT JOIN mcsf_post p
                                                                ON t.id = p.thread_id
                                                        ORDER BY p.created_on DESC
                                                ) a
                                                GROUP BY a.thread_id
                                                ;";

    const QUERY_RESULTSET_THREAD_LASTPOSTUSER = "SELECT a.thread_id as thread_id, a.last_post_user_id as last_post_user_id
                                                    FROM (
                                                            SELECT t.id as thread_id, u.id as last_post_user_id
                                                            FROM mcsf_thread t
                                                            LEFT JOIN mcsf_post p
                                                                ON t.id = p.thread_id
                                                            INNER JOIN user u
                                                                ON p.createdBy = u.id
                                                            ORDER BY p.created_on DESC
                                                    ) a
                                                    GROUP BY a.thread_id
                                                    ;";

    const QUERY_RESULTSET_FORUM_POSTCOUNT  = "SELECT f.id as forum_id, COUNT(p.id) as post_count
                                                FROM mcsf_forum f
                                                LEFT JOIN mcsf_thread t
                                                    ON f.id = t.forum_id
                                                LEFT JOIN mcsf_post p
                                                    ON p.thread_id = t.id
                                                GROUP BY f.id;";

    const QUERY_RESULTSET_FORUM_THREADCOUNT = "SELECT f.id as forum_id, COUNT(t.id) as thread_count
                                                FROM mcsf_forum f
                                                LEFT JOIN mcsf_thread t
                                                    ON f.id = t.forum_id
                                                GROUP BY f.id;";

    const QUERY_RESULTSET_FORUM_LASTPOSTID = "SELECT a.forum_id as forum_id, a.last_post_id as last_post_id
                                                FROM (
                                                    SELECT f.id as forum_id, p.id as last_post_id
                                                    FROM mcsf_forum f
                                                    LEFT JOIN mcsf_thread t
                                                        ON f.id = t.forum_id
                                                    LEFT JOIN mcsf_post p
                                                        ON t.id = p.thread_id
                                                    ORDER BY p.created_on DESC
                                                ) a
                                                GROUP BY a.forum_id
                                                ;";

    const QUERY_RESULTSET_FORUM_LASTPOSTUSERID = "SELECT a.forum_id as forum_id, a.last_post_user_id as last_post_user_id
                                                FROM (
                                                    SELECT f.id as forum_id, u.id as last_post_user_id
                                                    FROM mcsf_forum f
                                                    LEFT JOIN mcsf_thread t
                                                        ON f.id = t.forum_id
                                                    LEFT JOIN mcsf_post p
                                                        ON t.id = p.thread_id
                                                    LEFT JOIN user u
                                                        ON p.createdBy = u.id
                                                    ORDER BY p.created_on DESC
                                                ) a
                                                GROUP BY a.forum_id
                                                ";



    const TABLE_THREAD = 'mcsf_thread';
    const TABLE_FORUM  = 'mcsf_forum';

    private $conn;
    private $status;

    public function __construct()
    {
        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        $this->conn =  new \PDO("mysql:dbname=" . $this->container->getParameter('database_name') . ";host=" . $this->container->getParameter('database_host'), $this->container->getParameter('database_user'), $this->container->getParameter('database_password'), $options);
    }
    public function recacheAction(Request $request)
    {
        $this->conn->query("SET foreign_key_checks = 0;");
        # recache thread post_count
        $this->setStatus("Recaching thread post count");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_THREAD_POSTCOUNT);
        $this->updateDb($resultset, self::TABLE_THREAD, 'post_count', 'id');

        # recache thread last_post_id
        $this->setStatus("Recaching thread last_post_id");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_THREAD_LASTPOST);
        $this->updateDb($resultset, self::TABLE_THREAD, 'last_post_id', 'id');

        # recache thread last_post_user_id
        $this->setStatus("Recaching thread last_post_user_id");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_THREAD_LASTPOSTUSER);
        $this->updateDb($resultset, self::TABLE_THREAD, 'last_post_user_id', 'id');

        # recache forum post count
        $this->setStatus("Recaching forum post count");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_FORUM_POSTCOUNT);
        $this->updateDb($resultset, self::TABLE_FORUM, 'post_count', 'id');

        # recache forum thread count
        $this->setStatus("Recaching forum thread count");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_FORUM_THREADCOUNT);
        $this->updateDb($resultset, self::TABLE_FORUM, 'thread_count', 'id');

        # recache forum last_post_id
        $this->setStatus("Recaching forum last_post_id");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_FORUM_LASTPOSTID);
        $this->updateDb($resultset, self::TABLE_FORUM, 'last_post_id', 'id');

        # recache forum last_post_user_id
        $this->setStatus("Recaching forum last_post_user_id");
        $resultset = $this->fetchResultset(self::QUERY_RESULTSET_FORUM_LASTPOSTUSERID);
        $this->updateDb($resultset, self::TABLE_FORUM, 'last_post_user_id', 'id');

        $this->conn->query("SET foreign_key_checks = 1;");


        return new Response(json_encode("SUCCESS"));
    }

    public function fetchResultset($query)
    {
        return  $this->conn->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    public function updateDb($resultset, $table, $updatefield, $idfield)
    {
        $ids = implode(',', array_keys($resultset));
        $sql = sprintf("UPDATE %s SET %s = CASE %s ", $table, $updatefield, $idfield);
        foreach ($resultset as $id => $ordinal) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);
        }
        $sql .= "END WHERE id IN ($ids)";

        $this->conn->query($sql);
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
} 