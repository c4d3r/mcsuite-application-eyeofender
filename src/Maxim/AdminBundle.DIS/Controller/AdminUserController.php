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

        //Get groups
        $repository = $em->getRepository('MaximCMSBundle:Group');
        $groups		= $repository->findAll();

        $group_banned = $repository->findBy(array("id" => $config['admin']['ban']['webgroup']));

        //CHECK IF USER HAS BANNED RANK
        $group_contains = false;
        //$groups2 = array_diff((array)$groups, (array)$user->getGroup());


        for($i = 0; $i < count($groups); $i++)
        {
            foreach($user->getGroup() as $user_group)
            {
                if(isset($groups[$i]))
                {
                    if($groups[$i]->getId() == $user_group->getId())
                    {

                        unset($groups[$i]);
                    }
                }

                // CHECK FOR BANNED STATUS AS WELL
                if($user_group->getId() == $group_banned[0]->getId())
                {
                    $group_contains = true;
                }
            }
        }
        /* USER RANKS */
        $userGroups = $user->getGroup();
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
            "userGroups"		=> $user->getGroup(),
            "banned" 		=> $group_contains,
        );
        //CHECK IF USER HAS THIS ROLE

       // $data['accounts'] = $accounts;
        $data['groups'] = $groups;

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
            $user_groups 		= $request->request->get('_admin_user_groups');

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

                //Clear all groups, just read them afterwards, way faster
                //get current groups

                $user_current_groups = $user->getGroup();
                foreach($user_current_groups as $user_current_group)
                {
                    $user->removeGroup($user_current_group);
                }

                $repoGroup = $this->getDoctrine()->getRepository('MaximCMSBundle:Group');
                //add updated list
                if(isset($user_groups) && count($user_groups) > 0 && isset($user_groups[0])) {
                    foreach($user_groups as $user_group)
                    {
                        $groupadd = $repoGroup->findOneById($user_group);
                        $user->addGroup($groupadd);
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
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Group');
                $group = $repository->findOneBy(array("id" => $config['admin']['ban']['webgroup']));
                if($group)
                {
                    $user->addGroup($group);
                    $em->flush();
                    //SEND INGAME
                    $minecraft->send(array(1 => ModuleController::parseCommand(array("USER" => $username, "REASON" => $reason), $config['admin']['ban']['command'])));
                    $output = array("succes" => true, "message" => "User: ".$username." has been banned permanently");
                }
                else
                {
                    $output = array("succes" => true, "message" => "Could not find group with id: ".$config['admin']['ban']['webgroup']);
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
            $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Group');
            $group = $repository->findOneBy(array("id" => $config['admin']['ban']['webgroup']));
            $user->removeGroup($group);
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