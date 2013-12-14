<?php
/**
 * Project: MCSuite
 * File: AdminUserController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:25
 */

namespace Maxim\AdminBundle\Controller;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;

class AdminUserController extends Controller{

    public function userAction()
    {
        return $this->render('MaximAdminBundle:Users:index.html.twig', array("error" => NULL));
    }

    public function userAddAction()
    {
        $request = $this->getRequest();

        $user 				= new User();
        $user_username 		= $request->request->get('_admin_user_username');
        $user_email 		= $request->request->get('_admin_user_email');
        $user_lasIp 		= $request->request->get('_admin_user_lastIp');
        $user_lastLogin 	= $request->request->get('_admin_user_lastLogin');
        $user_dateOfBirth 	= $request->request->get('_admin_user_dateOfBirth');
        $user_location 		= $request->request->get('_admin_user_location');
        $user_skype 	 	= $request->request->get('_admin_user_skype');

        $user->setUsername($user_username);
        $user->setEmail($user_email);
        $user->setLastIp($user_lasIp);
        $user->setLastLogin($user_lastLogin);
        $user->setDateOfBirth(new \DateTime($user_dateOfBirth));
        $user->setLocation($user_location);
        $user->setSkype($user_skype);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $result = array('success' => true, 'message' => 'user added');

        return new Response(json_encode($result));
    }

    public function viewUserAction($userid)
    {
        //Get his info
        $em 		= $this->getDoctrine()->getManager();
        $config = Yaml::parse('../src/Maxim/CMSBundle/Resources/config/settings.yml');

        $repository = $em->getRepository('MaximCMSBundle:User');
        $user		= $repository->findOneBy(array("id" => $userid));

        //Get this user his minecraft accounts
       // $repository = $em->getRepository('MaximCMSBundle:Useraccounts');
       // $accounts	= $repository->findBy(array("user" => $user->getId()));

        //Get ranks
        $repository = $em->getRepository('MaximCMSBundle:Rank');
        $ranks		= $repository->findAll();

        $rank_banned = $repository->findBy(array("id" => $config['admin']['ban']['webgroup']));

        //CHECK IF USER HAS BANNED RANK
        $rank_contains = false;
        //$ranks2 = array_diff((array)$ranks, (array)$user->getRank());


        for($i = 0; $i < count($ranks); $i++)
        {
            foreach($user->getRank() as $user_rank)
            {
                if(isset($ranks[$i]))
                {
                    if($ranks[$i]->getId() == $user_rank->getId())
                    {

                        unset($ranks[$i]);
                    }
                }

                // CHECK FOR BANNED STATUS AS WELL
                if($user_rank->getId() == $rank_banned[0]->getId())
                {
                    $rank_contains = true;
                }
            }
        }
        /* USER RANKS */
        $userRanks = $user->getRank();
        $data['user'] = array(
            "id" 			=> $user->getId(),
            "username" 		=> $user->getUsername(),
            "email"			=> $user->getEmail(),
            "lastIp"		=> $user->getLastIp(),
            "lastLogin" 	=> $user->getLastLogin(),
            "dateOfBirth" 	=> $user->getDateOfBirth(),
            "location" 		=> $user->getLocation(),
            "skype" 		=> $user->getSkype(),
            "registeredOn" 	=> $user->getRegisteredOn(),
            "verified"		=> $user->getVerified(),
            "userRanks"		=> $user->getRank(),
            "banned" 		=> $rank_contains,
        );
        //CHECK IF USER HAS THIS ROLE

       // $data['accounts'] = $accounts;
        $data['ranks'] = $ranks;

        return $this->render('MaximAdminBundle:Users:profile.html.twig', $data);
    }

    public function userListAction() {

        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:User');


        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }

    public function userDeleteAction($userid)
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:User');
                $user = $repository->findOneBy(array('id' => $userid));

                if($user)
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($user);
                    $em->flush();
                    $result = array("success" => true, "message" => "User deleted succesfuly");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error deleting User");
                }
            }
            catch(\Exception $ex)
            {
                $result = $result = array("success" => false, "message" => "Error deleting User: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }
    public function userUpdateAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:User');

            $user_id 			= $request->request->get('_admin_user_id');
            $user_username 		= $request->request->get('_admin_user_username');
            $user_email 		= $request->request->get('_admin_user_email');
            $user_lasIp 		= $request->request->get('_admin_user_lastIp');
            $user_lastLogin 	= $request->request->get('_admin_user_lastLogin');
            $user_dateOfBirth 	= $request->request->get('_admin_user_dateOfBirth');
            $user_location 		= $request->request->get('_admin_user_location');
            $user_skype 	 	= $request->request->get('_admin_user_skype');
            $user_ranks 		= $request->request->get('_admin_user_ranks');

            $user = $repository->findOneById($user_id);

            if($user)
            {
                $em = $this->getDoctrine()->getManager();

                $user->setUsername($user_username);
                $user->setEmail($user_email);
                $user->setLastIp($user_lasIp);
                $user->setLastLogin(new \DateTime($user_lastLogin));
                $user->setDateofbirth(new \DateTime($user_dateOfBirth));
                $user->setLocation($user_location);
                $user->setSkype($user_skype);

                //Clear all ranks, just read them afterwards, way faster
                //get current ranks

                $user_current_ranks = $user->getRank();
                foreach($user_current_ranks as $user_current_rank)
                {
                    $user->removeRank($user_current_rank);
                }

                $repoRank = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank');
                //add updated list
                if(isset($user_ranks) && count($user_ranks) > 0 && isset($user_ranks[0])) {
                    foreach($user_ranks as $user_rank)
                    {
                        $rankadd = $repoRank->findOneById($user_rank);
                        $user->addRank($rankadd);
                    }
                }
                $em->flush();
                $result = array("success" => true, "message" => "user edited succesfuly");
            }
            else
            {
                $result = array("success" => false, "message" => "Error editing user: User with id ".$user_id." could not be found");
            }

            return new Response(json_encode($result));
        }
    }

    //ban user
    public function userBanAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = Yaml::parse('../src/Maxim/CMSBundle/Resources/config/settings.yml');
        $minecraft = $this->get('minecraft.helper');

        $request 	= $this->getRequest();
        $username 	= (String)$request->request->get('_ban_user');
        $time 		= (String)$request->request->get('_ban_time');
        $reason 	= (String)$request->request->get('_ban_reason');
        $permanent 	= (boolean)$request->request->get('_ban_permanent');
        //RELOAD USER FROM DATABASE
        $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:User');
        $user = $repository->findOneBy(array("username" => $username));
        if($user)
        {
            if($permanent)
            {
                //GET BANNED RANK
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank');
                $rank = $repository->findOneBy(array("id" => $config['admin']['ban']['webgroup']));
                if($rank)
                {
                    $user->addRank($rank);
                    $em->flush();
                    //SEND INGAME
                    $minecraft->send(array(1 => ModuleController::parseCommand(array("USER" => $username, "REASON" => $reason), $config['admin']['ban']['command'])));
                    $output = array("succes" => true, "message" => "User: ".$username." has been banned permanently");
                }
                else
                {
                    $output = array("succes" => true, "message" => "Could not find rank with id: ".$config['admin']['ban']['webgroup']);
                }
            }
            else
            {
                //SEND INGAME
                $minecraft->send(array(1 => ModuleController::parseCommand(array("USER" => $username, "REASON" => $reason, "TIME" => $time), $config['admin']['tempban']['command'])));
                $output = array("succes" => true, "message" => "User: ".$username." has been banned for time: ".$time);
            }
        }
        else
        {
            $output = array("succes" => true, "message" => "could not ban user:" . $username);
        }

        return new response(json_encode($output));

    }
    public function userUnbanAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = Yaml::parse('../src/Maxim/CMSBundle/Resources/config/settings.yml');
        $minecraft = $this->get('minecraft.helper');

        $request 	= $this->getRequest();
        $username 	= (String)$request->request->get('_ban_user');
        //RELOAD USER FROM DATABASE
        $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:User');
        $user = $repository->findOneBy(array("username" => $username));
        if($user)
        {
            //REMOVE UNBAN WEB RANK
            $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank');
            $rank = $repository->findOneBy(array("id" => $config['admin']['ban']['webgroup']));
            $user->removeRank($rank);
            $em->flush();
            //SEND INGAME
            $minecraft->send(array(1 => ModuleController::parseCommand(array("USER" => $username), $config['admin']['unban']['command'])));
            $output = array("success" => true, "message" => "User: ".$username." has been unbanned");
        }
        else
        {
            $output = array("success" => true, "message" => "could not unban user:" . $username);
        }

        return new response(json_encode($output));
    }

}