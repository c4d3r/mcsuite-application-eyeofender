<?php
namespace Maxim\CMSBundle\Helper;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class MinecraftHelper
{

    const SERVER_AUTHENTICATE = "https://authserver.mojang.com/authenticate";
    const AGENT_NAME = "Minecraft";
    const AGENT_VERSION = 1;

    protected $logger;
    protected $rest;

    public function __construct($logger, RESTHelper $rest)
    {
        $this->logger = $logger;
        $this->rest = $rest;
    }


     public function signIn($username, $password)
     {
         //first check old system
         $oldsignin = $this->Oldsignin($username, $password);
         if($oldsignin != false && ($oldsignin['success'] && ($oldsignin['account'] == $username))) {
             return $oldsignin;
         }

         $payload['agent'] = array('name' => self::AGENT_NAME, 'version' => self::AGENT_VERSION);
         $payload['username'] = $username;
         $payload['password'] = $password;

         $headers = array(
             'Content-Type: application/json',
         );

         $response = $this->rest->execute(RESTHelper::METHOD_POST, $headers, self::SERVER_AUTHENTICATE, json_encode($payload));
         $response = (array)json_decode($response->getData(), true);
         if(isset($response['error']))
         {
             $message = "An error occured please try again later";
             $errorMessage = $response['errorMessage'];

             # review error types to send more details to the user
             switch($response['error'])
             {
                 case "ForbiddenOperationException":
                     $message = $response['errorMessage'];
                     break;

                 # Selecting profiles isn't implemented yet.
                 case "IllegalArgumentException":
                     break;

                 # Non-existing endpoint was called.
                 case "Not Found":
                     break;

                 # Something other than a POST request was received.
                 case "Method Not Allowed":
                     break;

                 # Data was not submitted as application/json
                 case "Unsupported Media Type":
                     break;

                 # Unknown error
                 default:
                     $errorMessage = print_r($response);
             }

             //$this->logger->info("[MINECRAFT API]" . $errorMessage);
             return array("success" => false, "message" => $message);
         }
         elseif(isset($response['selectedProfile']))
         {
             # user authenticated
             return array("success" => true, "account" => $response['selectedProfile']);
         }

         return array("success" => false, "message" => "An unknown error occured");
     }


    /**
     * @description Signs a user in to their Minecraft account and returns their account details.
     * @param $username
     * @param $password
     * @param int $version
     * @return array|bool
     */
    /*public function signIn($username, $password, $version = 17)
    {
        $parameters = array('user' => $username, 'password' => $password, 'version' => $version);
        $request = $this->request('http://login.minecraft.net/', $parameters);
        $response = explode(':', $request);
        $this->logger->err(print_r($response, true));
        if (count($response) >= 0) {
            return array(
                "success" => true,
                "account" => array(
                    "name" => $response[2]
                ),

            );
            /* $this->account = array(
                 'current_version' => $response[0],
                 'correct_username' => $response[2],
                 'session_token' => $response[3],
                 'premium_account' => $this->isPremium($response[2]),
                 'player_skin' => $this->getSkin($response[2]),
                 'request_timestamp' => date("dmYhms", mktime(date('h'), date('m'), date('s'), date('m'), date('d'), date('y')))
             );
        }
        return array("success" => false, "message" => "Incorrect username or password");
    }*/

    public function get_skin($username)
    {
        if ($this->is_premium($username))
        {
            $headers = get_headers('http://s3.amazonaws.com/MinecraftSkins/'.$username.'.png');
            if(isset($headers[7]))
            {
                if ($headers[7] == 'Content-Type: image/png' || $headers[7] == 'Content-Type: application/octet-stream')
                {
                    return 'https://s3.amazonaws.com/MinecraftSkins/'.$username.'.png';
                }
                else
                {
                    return 'https://s3.amazonaws.com/MinecraftSkins/char.png';
                }
            }
            else
            {
                return 'https://s3.amazonaws.com/MinecraftSkins/char.png';
            }
        }
        else
        {
            return false;
        }
    }

    public function keep_alive($username, $session) {
        $parameters = array('name' => $username, 'session' => $session);
        $request = $this->request('https://login.minecraft.net/session', $parameters);
        return null;
    }

    public function join_server($username, $session, $server) {
        $parameters = array('user' => $username, 'sessionId' => $session, 'serverId' => $server);
        $request = $this->request('http://session.minecraft.net/game/joinserver.jsp', $parameters);
        if ($request != 'Bad login') {
            return true;
        } else {
            return false;
        }
    }

    public function check_server($username, $server) {
        $parameters = array('user' => $username, 'serverId' => $server);
        $request = $this->request('http://session.minecraft.net/game/checkserver.jsp', $parameters);
        if ($request == 'YES') {
            return true;
        } else {
            return false;
        }
    }

    public function render_skin($username, $render_type, $size) {
        if (in_array($render_type, array('head', 'body'))) {
            if ($render_type == 'head') {
                header('Content-Type: image/png');
                $canvas = imagecreatetruecolor($size, $size);
                $image = imagecreatefrompng($this->get_skin($username));
                imagecopyresampled($canvas, $image, 0, 0, 8, 8, $size, $size, 8, 8);
                imagecopyresampled($canvas, $image, 0, 0, 40, 8, $size, $size, 8, 8);
                return imagepng($canvas);
            } else if($render_type == 'body') {
                header('Content-Type: image/png');
                $scale = $size / 16;
                $canvas = imagecreatetruecolor(16*$scale, 32*$scale);
                $image = imagecreatefrompng($this->get_skin($username));
                imagealphablending($canvas, false);
                imagesavealpha($canvas,true);
                $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
                imagefilledrectangle($canvas, 0, 0, 16*$scale, 32*$scale, $transparent);
                imagecopyresized  ($canvas, $image, 4*$scale,  0*$scale,  8,   8,   8*$scale,  8*$scale,  8,  8);
                imagecopyresized  ($canvas, $image, 4*$scale,  8*$scale,  20,  20,  8*$scale,  12*$scale, 8,  12);
                imagecopyresized  ($canvas, $image, 0*$scale,  8*$scale,  44,  20,  4*$scale,  12*$scale, 4,  12);
                imagecopyresampled($canvas, $image, 12*$scale, 8*$scale,  47,  20,  4*$scale,  12*$scale, -4,  12);
                imagecopyresized  ($canvas, $image, 4*$scale,  20*$scale, 4,   20,  4*$scale,  12*$scale, 4,  12);
                imagecopyresampled($canvas, $image, 8*$scale,  20*$scale, 7,   20,  4*$scale,  12*$scale, -4,  12);
                return imagepng($canvas);
            }
        } else {
            return false;
        }
    }
    public function renderCharAction($char)
    {

        $content = $this->render_skin($char, 'head', 50);

        $response = new Response();
        $response->clearHttpHeaders();
        $response->setContent($content);
        $response->headers->set('Content-Type', 'image/png');

        return $response;
    }
    public function parseCommand($command, $tags)
    {
        if (sizeof($tags) > 0)
        {
            foreach ($tags as $tag => $data)
            {
                $command = str_replace("{" . $tag . "}", $data, $command);
            }
        }
        return $command;
    }

    private function request($website, array $parameters)
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, 1);
        if ($parameters != null) {
            curl_setopt($request, CURLOPT_URL, $website.'?'.http_build_query($parameters, null, '&'));
        } else {
            curl_setopt($request, CURLOPT_URL, $website);
        }
        $response = curl_exec($request);
        $details = curl_getinfo($request);
        curl_close($request);
        return ($details['http_code'] == 200) ? $response : null;
    }
    public function Oldsignin($username, $password, $version = 99999)
    {
        $sites = array(
            "http://session.minecraft.net/game/getversion.jsp",
            "http://login.minecraft.net/",
            "https://login.minecraft.net/"
        );

        $parameters = array('user' => $username, 'password' => $password, 'version' => $version);
        $request = $this->request('https://login.minecraft.net/', $parameters);

        $response = explode(':', $request);

        if (count($response) == 1) {
            $output = "Failed to authenticate.";
            switch(strtolower($response[0]))
            {
                case "account migrated, use e-mail as username.":
                    $output = "account migrated, use e-mail as username.";
                    break;
                case "minecraft.net: too many failed logins":
                    $output = "too many failed logins";
                    break;
                case "bad request":
                    $output = "Bad request";
                    break;
            }
            return array("success" => false, "message" => $output);
        }
        else if (count($response) > 2) {

            $account = array(
                'current_version'   => $response[0],
                'correct_username'  => $response[2],
                'session_token'     => $response[3],
                'premium_account'   => $this->isPremium($username),
                //'player_skin'       => $this->getSkin($response[2]),
                'request_timestamp' => date("dmYhms", mktime(date('h'), date('m'), date('s'), date('m'), date('d'), date('y')))
            );

            return array("success" => true, "message" => $account, "account" => array("name" => $response[2]));
        }
        return array("success" => false, "message" => "Unknown error");
    }
    public function isPremium($username)
    {
        $parameters = array('user' => $username);
        return $this->request('http://www.minecraft.net/haspaid.jsp', $parameters);
    }
    public function send($cmd = array(), $return = false)
    {
        $config = $this->container->getParameter('maxim_cms');
        $logger = $this->container->get('logger');
        $logger->info("[MCS]: got command");

        $host = $config['plugin']['host'];
        $port = $config['plugin']['port'];
        $user = $config['plugin']['username'];
        $pass = $config['plugin']['password'];

        # CREATE SOCKET
        $socket = false;
        try
        {
            if(!($sock = socket_create(AF_INET, SOCK_STREAM, 0)))
            {
                $logger->err("[CONNECTION]: error creating socket: ".socket_strerror(socket_last_error()));
                return false;
            }
            // Set timeout
            // socket_set_option($sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 5, 'usec' => 0));
            if(!($succ = socket_connect($sock, $host, $port)))
            {
                $logger->err("[CONNECTION]: error connecting socket: ".socket_strerror(socket_last_error()));
                return false;
            }
            $socket = true;
        }
        catch(\Exception $ex)
        {
            $logger->err("[CONNECTION]: error connecting to host: ".$ex->getMessage());
            return false;
        }

        # check if socket is made, otherwise don't continue
        if($socket === false)
        {
            return false;
        }

        $command[0] = "login=".$user;
        $command[1] = "password=".$pass;

        $command = array_merge($command, (array)array_merge($cmd, array("\n")));

        $command = implode(";", $command);

        // send all commands
        try
        {
            @socket_write($sock, $command);
        }
        catch(\Exception $ex)
        {
            $logger->err("[CONNECTION]: error writing to socket: ".$ex->getMessage());
            return false;
        }
        $count=0;
        try
        {
            while(($result=socket_read($sock,32767,PHP_NORMAL_READ)) != "---ENDSESSION---\n")
            {
                //CHECK IF LAST LETTER IS a seperator
                if(substr($result, strlen($result) -1, strlen($result)) == ",")
                {
                    $result = substr($result,0,strlen($result) -1);
                }
                else
                {
                    $result = $result;
                }
                // Lets get rid of the \n at the end of the result

                // Add each result to an array so we may access them as needed.
                $results[$count++]=$result;
            }
        }
        catch(\Exception $ex)
        {
            $logger->err("[CONNECTION]: error reading from socket: ".$ex->getMessage());
            return false;
        }
        // Lets make sure we logged in ok if not index zero will = "LOGINERROR"
        if($results[0] == "LOGINERROR")
        {
            $logger->err("[CONNECTION]: wrong credentials when connecting to MCS plugin");
        }
        else
        {
            if($return)
            {
                // We can check out results with a zero based index
                // Lets get the Online Player Count. This was the third command we sent so
                // on a zero base 0, 1, 2  : 2 is the index of the third command so...
                // First make sure we did not get and error for this command.
                if((stripos($result[1],"ERROR") != 0) && (stripos($result[1],"UNKNOWN") != 0))
                {
                    $logger->err("[CONNECTION]: error when executing command to MCS plugin");
                }
                else
                {
                    //No Error or Unknown so get the value.
                    return array_slice($results, 0, count($result));
                }
            }
        }

    }
}
