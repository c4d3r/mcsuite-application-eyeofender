<?php
namespace Maxim\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Maxim\CMSBundle\Entity\Useraccounts;
use Maxim\CMSBundle\Entity\Userfriend;
use Maxim\CMSBundle\Controller\ModuleController;

class AccountController extends Controller
{
	public function indexAction()
	{
		return $this->render('MaximCMSBundle:pages:account.html.twig');
	}
	#Minecraft Accounts
	public function minecraftViewAction()
	{
		//VIEW MINECRAFT ACCOUNTS
		//Load News

    	$em = $this->getDoctrine()->getManager();
		$user = $this->get('security.context')->getToken()->getUser();
		//SELECT news.id, news.title, news.content, news.date, news.user_id, COUNT(comment.id) as comments FROM news LEFT JOIN comment ON news.id = comment.newsId GROUP BY news.id
		$qb = $em->createQueryBuilder()
		      ->select('a')
		      ->from('MaximCMSBundle:Useraccounts', 'a')
			  ->innerJoin('MaximCMSBundle:User', 'u', 'WITH', 'a.user = u.id')
			  ->where('a.user = :userid')
			  ->setParameter(':userid', $user)
			  ->orderBy('a.accountname', 'ASC');
		;
    $tags = $qb->getQuery()->getResult();

    $data['accounts'] = $tags;
    return $this->render('MaximCMSBundle:pages:account/minecraft.html.twig', $data);
}
	public function addAction()
	{
		$request = $this->getRequest();
		if ($request->isXmlHttpRequest()) {

			#Get requests
			$mcUser = $request->request->get('_minecraft_name');
			$enteredCode = $request->request->get('_minecraft_code');
			
			if(!empty($mcUser))
			{
				#Create security code
				$code = substr(sha1(strtoupper($mcUser) . ':' . strtoupper($this->container->getParameter('maxim_cms.server.name')) . ':' . $this->container->getParameter('maxim_cms.account.codeSalt')), 0, $this->container->getParameter('maxim_cms.account.codeLength'));
				
				if(!empty($enteredCode))
				{
					if($enteredCode == $code)
					{
						$em = $this->getDoctrine()->getManager();
						$userExists = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $mcUser));
						if(!$userExists)
						{
							$useraccount = new Useraccounts();
							$user = $this->get('security.context')->getToken()->getUser();
							
							$useraccount->setAccountname($mcUser);
							$useraccount->setUser($user);
							
							$validator = $this->get('validator');
						    $errors = $validator->validate($useraccount);
						
						    if (count($errors) > 0) {
						        $result = array('success' => false, 'message' => "error");	
						    } else {
						        $em->persist($useraccount);
								$em->flush();

								if($this->container->getParameter('maxim_cms.account.send.enabled') == true)
								{
									$minecraft = $this->get('minecraft.helper');
									$minecraft->send(array(1 => ModuleController::parseCommand(array("USER" => $mcUser), $this->container->getParameter('maxim_cms.account.send.command'))));
								}
								
								$result = array('success' => true, 'message' => "Your account has been added succesfuly, reloading");	
						    }
						}
						else {
							$result = array('failed' => true, 'message' => 'This account has already been added');	
						}
					}
					else
					{
						$result = array('failed' => true, 'message' => 'unable to verify your ingame account');		
					}
				}
				else
				{
					$result = array('failed' => true, 'message' => 'Field entered code is required');			
				}
			}
			else
			{
				$result = array('failed' => true, 'message' => 'Field username is required');			
			}
			
		}
		else
		{
			$result = array('success' => false, 'message' => 'How you got here?');		
		}
		return new Response(json_encode($result));
	}
	public function getAccountId($username)
	{
		$em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $username));
			
		if (!$user) {
	        //throw $this->createNotFoundException('Incorrect key, email verification failed: '.$email[0]);
	        return false;
	    }
		else
		{
			return $user[0]['id'];
		}
	}
	
	#Profile
	public function profileAction($username)
	{
		$em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $username));
		if(!$user)
		{
			#user is not registered
			
		}
		else
		{
			#user is registered
			$em = $this->getDoctrine()->getManager();
			//SELECT * FROM user_friend LEFT JOIN user ON user_friend.friend_user_id = user.id WHERE user_friend.user_id = 1
			$qb = $em->createQueryBuilder()
			      ->select('u')
			      ->from('MaximCMSBundle:Userfriend', 'f')
				  ->leftJoin('MaximCMSBundle:User', 'u', 'WITH', 'f.friend = u.id')
				  ->where('f.user = :userid')
				  ->setParameter(':userid', $user)
			;
			$tags = $qb->getQuery()->getResult();
			
			$data['friends'] = $tags;
			$data['Owner_profile'] = $username;
			
			
		}
		$data['player'] = $username;
		return $this->render('MaximCMSBundle:pages:profile.html.twig', $data);
	}
	public function accountViewAction()
	{
		#View account settings
		#---------------------
		#Load account settings
		$user = $this->get('security.context')->getToken()->getUser();
		
		$result['setting'] = array(	"email" 	=> $user->getEmail(), 
									"lastIp" 	=> $user->getLastIp(), 
									"lastLogin" => $user->getLastLogin(), 
									"location" 	=> $user->getLocation(), 
									"skype" 	=> $user->getSkype(), 
									"username" 	=> $user->getUsername(),
									"age"		=> ModuleController::calcAge($user->getDateOfBirth())
									);
		
		return $this->render('MaximCMSBundle:pages:account/profile.html.twig', $result);
	}
	public function accountSaveAction()
	{
		$securityContext = $this->container->get('security.context');
		
		if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') )
		{
			$user 		= $securityContext->getToken()->getUser();
			$request 	= $request = $this->getRequest();
			$skype 		= $request->request->get('_user_skype');
			$email 		= $request->request->get('_user_email');
			
			$em = $this->getDoctrine()->getManager();
			
			$user->setSkype($skype);
			$user->setEmail($email);
			$user->setPasswordConfirm($user->getPassword());
			$validator = $this->get('validator');
		    $result = $validator->validate($user);
			
		    if (!(count($result) > 0)) {
				$em->flush($user);
				$result = array("success" => true, "message" => "your details have been saved");	
			}
			else
			{
				$result = array('success' => false, 'message' => $result[0]->getMessage());	
			}
			
			return new Response(json_encode($result));
		}
	}
	public function profileAjaxAction($username)
	{
		$em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $username));
		if(!$user)
		{
			#user is not registered
			
		}
		else
		{
			#user is registered
			$em = $this->getDoctrine()->getManager();
			//SELECT * FROM user_friend LEFT JOIN user ON user_friend.friend_user_id = user.id WHERE user_friend.user_id = 1
			$qb = $em->createQueryBuilder()
			      ->select('u')
			      ->from('MaximCMSBundle:Userfriend', 'f')
				  ->leftJoin('MaximCMSBundle:User', 'u', 'WITH', 'f.friend = u.id')
				  ->where('f.user = :userid')
				  ->setParameter(':userid', $user)
			;
			$tags = $qb->getQuery()->getResult();
			
			$data['friends'] = $tags;
			$data['Owner_profile'] = $username;
			
			
		}
		$data['player'] = $username;
		return $this->render('MaximCMSBundle:pages:profile_ajax.html.twig', $data);
	}
	#Friends
	public function viewFriendsAction($username)
	{
		#get user id
		$em = $this->getDoctrine()->getManager();
	    $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("username" => $username));
			
		if($user)
		{
			$em = $this->getDoctrine()->getManager();
			//SELECT * FROM user_friend LEFT JOIN user ON user_friend.friend_user_id = user.id WHERE user_friend.user_id = 1
			$qb = $em->createQueryBuilder()
			      ->select('u')
			      ->from('MaximCMSBundle:Userfriend', 'f')
				  ->leftJoin('MaximCMSBundle:User', 'u', 'WITH', 'f.friend = u.id')
				  ->where('f.user = :userid')
				  ->setParameter(':userid', $user)
			;
			$tags = $qb->getQuery()->getResult();
	
			$data['player'] = $username;
			$data['friends'] = $tags;
				
			$data['Owner_profile'] = $username;
	
			return $this->render('MaximCMSBundle:pages:profile.html.twig', $data);
		}
		else {
			$this->get('session')->setFlash('error', "Profile could not be loaded");
			return $this->forward('MaximCMSBundle:Default:index');
		}
		
		
	}//View the specified user his friends, return array
	public function friendDeleteAction($username, $friend)
	{
		#logged user
		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getManager();

		#Check if it's his profile
		#First check if user is logged in
		$friendAccount = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $friend));

		if(!$friendAccount)
		{
			$this->get('session')->setFlash('error', "Friend not found");
		}
		else
		{
		    $userFriend = $em->getRepository('MaximCMSBundle:Userfriend')->findOneBy(array("friend" => $friendAccount, "user" => $user));
			
			if($userFriend)
			{
				$em->remove($userFriend);
				$em->flush();
				$this->get('session')->setFlash('notice', "$username removed as friend");
			}
			else {
				$this->get('session')->setFlash('error', "$username not found as your friend");
			}
			
		}
		return $this->forward('MaximCMSBundle:Account:viewFriends', array(
							        'username'  => $username
							    ));

	}
	public function friendAddAction($username)
	{
		#Requires to be logged in
		if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
			
		    $em = $this->getDoctrine()->getManager();
	   		$userToAdd = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $username));
			$userToAdduser = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $userToAdd->getUser()));
			
			$user = $this->get('security.context')->getToken()->getUser();
			
			if (!$userToAdd) {
				throw $this->createNotFoundException('The user does not exist');
			}
			else
			{
				$Userfriend = new Userfriend();
				$Userfriend->setFriend($userToAdduser);
				$Userfriend->setUser($user);
				
				
				$validator = $this->get('validator');
		    	$result = $validator->validate($Userfriend);
			
		    	if (!(count($result) > 0)) {
					$this->get('session')->setFlash('notice', "$username added as friend");
					$em->persist($Userfriend);
					$em->flush();
				}
				else
				{
					$this->get('session')->setFlash('error', $result[0]->getMessage());
				}
				return $this->forward('MaximCMSBundle:Account:viewFriends', array(
							        'username'  => $username
							    ));
			}
		}
	}
	
}