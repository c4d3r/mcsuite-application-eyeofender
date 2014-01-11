<?php
// FYI I took some code out that wasn't needed to just get things going. 
// (Annotations an stuff so don't pay too close attention to the use directives bellow, I beleive you wouldn't need route here because were not using route annotations.. but I do in my projects.)

namespace Maxim\CMSBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Yaml\Yaml;

//If it inherits from ModuleController then it can be overwrited!!
class SecurityController extends Controller
{

    protected $details = array();

    /* LOGIN */
    public function loginAction()
    {
        $request = Request::createFromGlobals();
        $session = new Session();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('MaximCMSBundle:Security:login.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    public function loginFormAction()
    {
        $request = Request::createFromGlobals();
        $session = new Session();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('MaximCMSBundle:Security:loginForm.html.twig', array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    /* REGISTER */
    public function registerViewAction()
    {
        return $this->render('MaximCMSBundle:pages:register.html.twig');
    }
    public function validateAction($key)
    {
        $config = $this->container->getParameter('maxim_cms');
        $logger = $this->container->get('logger');
        $security = $this->get('security.helper');

        //Get the key
        $string = $security->decrypt($key, $config['register']['validation']['salt']);
        $key = explode(':', $string);

        $email  = $key[0];
        $userid = $key[1];
        $time   = $key[2];

        $logger->info('REGISTRATION: validation with email: '.$email.', userid: '.$userid.', time: '.$time);
        //Check if time is less than default
        //TRY to find the user
        try
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $userid));

            if(!$user)
            {
                if($user->getVerified() == 0)
                {
                    if(($time + $config['register']['validation']['time'])  >= time())
                    {
                        //CHANGE IT'S VERIFIED FLAG
                        $user->setVerified(1);
                        $output = array('success' => true, 'message' => 'Account has been succesfuly verified, you may login now');
                        $logger->info('REGISTRATION: user with id: '.$userid.' has been verified');
                    }
                    else
                    {
                        //REMOVE THE USER
                        $em->remove($user);
                        $output = array('success' => false, 'message' => 'The verification key has expired, please register again.');
                        $logger->err('REGISTRATION: validation key expired for user with id: '.$userid);
                    }
                    $em->flush();
                }
                else
                {
                    $output = array('success' => false, 'message' => 'This account has already been verified');
                }
            }
            else
            {
                $output = array('success' => false, 'message' => 'We could not find the user specified, please try again later');
                $logger->err('REGISTRATION: can not find user with id: '.$userid);
            }
        }
        catch(\Exception $ex)
        {
            $logger->err("REGISTRATION: error while validating user");
        }

        $data['register_verified'] = $output;
        return $this->render('MaximCMSBundle:pages:register.html.twig', $data);

    }


    public function fetchRegisterVariables()
    {
        $request = Request::createFromGlobals();

        $this->details['email']            = $request->request->get('_register_email');
        $this->details['username']         = $request->request->get('_register_username');
        $this->details['password']         = $request->request->get('_register_password');
        $this->details['password_confirm'] = $request->request->get('_register_password_confirm');
        $this->details['location']         = $request->request->get('_register_location');
        $this->details['dob']['day']       = $request->request->get('_register_dob_day');
        $this->details['dob']['month']     = $request->request->get('_register_dob_month');
        $this->details['dob']['year']      = $request->request->get('_register_dob_year');
        $this->details['skype']            = $request->request->get('_register_skype');

        //set default minecraft verification
        $this->details['minecraft']        = $request->request->get('_minecraft') == "true" ? true : false;
        $this->details['mcpass']           = $request->request->get('_mcpass');
        $this->details['mcuser']           = $request->request->get('_mcuser');
        $this->details['verified']         = false;
        $this->details['verification_mc']  = "We were unable to verify your username with the minecraft servers";
    }
    /**
     * Register Action
     */
    public function registerAction()
    {
        $request = Request::createFromGlobals();
        $config = $this->container->getParameter('maxim_cms');
        $logger = $this->container->get('logger');

        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedException("Unknown request");
        }

        $this->fetchRegisterVariables();

        # minecraft user credentials validation
        $minecraft = $this->get('minecraft.helper');

        $this->details['verified'] = $minecraft->signIn($this->details['mcuser'], $this->details['mcpass']);

        if(!$this->details['verified']["success"])
        {
            $this->details['verified']['message'] = "Minecraft.net - " . $this->details['verified']['message'];
            return new Response(json_encode($this->details['verified']));
        }
        else if(strtoupper(trim($this->details['verified']['account']["name"])) != strtoupper(trim($this->details['username'])))
        {

            /*$logger->err("USERNAME:" . $this->details['mcuser']);
            $logger->err(print_r($this->details['verified'], true));
            $logger->err(print_r($this->details['username'], true));
            $logger->err($this->details['verified']['account']["name"]);
            $logger->err($this->details['username']);         */
            return new Response(json_encode(array("success" => false, "message" => "Please use your minecraft username as the website username")));
        }

        $user = new User();
        $user->setEmail($this->details['email']);
        $user->setUsername($this->details['username']);
        $user->setPassword($this->details['password']);
        $user->setLocation($this->details['location']);
        $user->setPasswordConfirm($this->details['password_confirm']);
        $user->setSkype($this->details['skype']);

        try
        {
            $datetime = new \DateTime();
            $datetime->setDate($this->details['dob']['year'], $this->details['dob']['month'], $this->details['dob']['day']);
            $user->setDateofbirth($datetime);
        }
        catch(\Exception $ex)
        {
            $logger->err("REGISTER: " . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "Please enter a correct date")));
        }

        $user->setRegisteredOn(new \DateTime("now"));

        //set this default value depending wheter you want it enabled or not, for future use, otherwise default value
        if($config['register']['validation']['enabled'] == false){
            $user->setVerified(1);
        }

        //check if user account was verified
        if(!$this->details['verified'] == true){
            return array("success" => false, "message", $this->details['verification_mc']);
        }

        //validate the remaining fields
        $validator = $this->get('validator');
        $result = $validator->validate($user);

        //If errors, display them
        if ((count($result) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $result[0]->getMessage())));
        }

        # no validation errors
        $factory  = $this->get('security.encoder_factory');
        $encoder  = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($encodedPassword);

        # insert user
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        //log that the user has been registered succesfully
        $logger->info('REGISTRATION: user '.$this->details['username'].' registrated succesfuly!');

        //If register validation flag is true
        if($config['register']['validation']['enabled'] == true)
        {
            $this->registerValidation($user->getId());
        }
        else if($config['register']['mail']['enabled'] == true)
        {
            $this->registerMail();
        }

        #Create a login token
        $token = new UsernamePasswordToken($user, null, 'main', array('ROLE_USER'));
        $this->get('security.context')->setToken($token);

        #return all this
        return new Response(json_encode(array('success' => true, 'message' => 'Registrated successfully', "redirect" => $this->generateUrl("home"))));
    }

    public function registerValidation($userid)
    {
        $logger   = $this->get('logger');
        $config   = $this->container->getParameter('maxim_cms');
        $security = $this->get('security.helper');

        $logger->err("TEST REGISTER MAIL");
        $logger->err(print_r($config, true));

        //Generate validation key
        $salt = $config['register']['validation']['salt'];
        $uri = $this->get('router')->generate('register_validation', array('key' => $security->encrypt(($this->details['email'] . ':' . $userid . ':' .time()), $salt)));
        $message = '<br/>
                    <a href="'.$config['general']['domain'].$uri.'" style="color:#888888; text-decoration:underline;">'.$config['general']['domain'].$uri.'</a>';
        #SEND MAIL
        try
        {
            $message = \Swift_Message::newInstance()
                ->setSubject($config['register']['validation']['subject'])
                ->setFrom($config['emails']['info'])
                ->setTo($this->details['email'])
                ->setBody($this->get('templating')->render('MaximCMSBundle:email:registration.html.twig', array('user' => $this->details['username'], 'message' => ($config['register']['validation']['message'].$message))))
                ->setContentType("text/html")
            ;
            $this->get('mailer')->send($message);
            $logger->info('REGISTER: verification mail has been sent to: '.$this->details['email']);
        }
        catch(\Exception $ex)
        {
            //error while sending mail
            $logger->err('REGISTER: Could not send mail to '.$this->details['email'].': '.$ex->getMessage());
        }
    }
    public function registerMail()
    {
        $config   = $this->container->getParameter('maxim_cms');
        $logger   = $this->get('logger');

        #SEND MAIL

        try
        {
            $message = \Swift_Message::newInstance()
                ->setSubject($config['register']['mail']['subject'])
                ->setFrom($config['emails']['info'])
                ->setTo($this->details['email'])
                ->setBody($this->get('templating')->render('MaximCMSBundle:email:registration.html.twig', array('user' => $this->details['username'], 'message' => $config['register']['mail']['message'])))
                ->setContentType("text/html")
            ;
            $this->get('mailer')->send($message);
        }
        catch(\Exception $ex)
        {
            //error while sending mail
            $logger->err('REGISTER: Could not send mail: '.$ex->getMessage());
        }
    }
    public function membersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();
        if($user)
        {
            $query   = $em->createQuery("SELECT p FROM MaximCMSBundle:Purchase p INNER JOIN MaximCMSBundle:StoreItem s WITH p.storeItem = s.id INNER JOIN MaximCMSBundle:User u WITH u.id = p.user WHERE u.id = :userid ORDER BY p.date DESC");
            $query->setParameters(array("userid" => $user->getId()));

            $purchases = $query->getResult();
            $data['purchase'] = $purchases[0];
        }
        return $this->render('MaximCMSBundle:Security:member.html.twig', $data); # This is just a holding place for your memberships features.
    }

    public function changePassViewAction()
    {
        return $this->render('MaximCMSBundle:Modules:account/changepass.html.twig');
    }
    public function changePassAction()
    {
        $request = Request::createFromGlobals();

        if (!$request->isXmlHttpRequest()) {
            return new Response(json_encode(array('success' => false, 'message' => 'No POST request found!')));
        }
        $userS = $this->get('security.context')->getToken()->getUser();
        $factory = $this->get('security.encoder_factory');

        $npass  = $request->request->get('_password');
        $npassc = $request->request->get('_password_confirm');

        if(!($npass == $npassc)) {
            return new Response(json_encode(array('success' => false, 'message' => 'Confirm password did not match')));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MaximCMSBundle:User')->find($userS->getId());

        $encoder  = $factory->getEncoder($userS);
        $encodedPassword = $encoder->encodePassword($request->request->get('_password_current'), $user->getSalt());

        if(!($userS->getPassword() == $encodedPassword)) {
            //password doesn't match current password
            return new Response(json_encode(array('success' => false, 'message' => 'current password did not match')));
        }

        $encodedPassword = $encoder->encodePassword($npass, $user->getSalt());

        $user->setPassword($encodedPassword);
        $em->flush();
        //change pass
        return new Response(json_encode(array('success' => true, 'message' => 'Password changed succesfuly')));
    }

    public function forgotPassViewAction()
    {
        return $this->render('MaximCMSBundle:Modules:account/forgotpass.html.twig');
    }
    public function forgotPassSendAction()
    {
        $request = Request::createFromGlobals();
        $security = $this->get('security.helper');

        if ($request->isXmlHttpRequest()) {
            throw new AccessDeniedException("Not a valid post request");
        }
        //TODO: remove Yaml:parse setteings
        $config   = $this->container->getParameter('maxim_cms');

        $salt = $config['forgotPassword']['salt'];

        $email = $request->request->get('_email');
        # small validation
        if(!isset($email) || trim($email) == "") {
            return new Response(json_encode(array("success" => false, "message" => "Please fill in your email")));
        }

        if(!$this->validEmail($email)) {
            return new Response(json_encode(array("success" => false, 'message' => 'E-mail is incorrect')));
        }
        $uri = $this->get('router')->generate('account_forgot_password_reset', array('key' => $security->encrypt(($email . ':' . time()), $salt)));

        $message = 'Please follow the link beneath to reset your password <br/>
        <a href="'.$config['general']['domain'].$uri.'" style="color:#888888; text-decoration:underline;">RESET PASSWORD</a>';

        $mail = \Swift_Message::newInstance()
            ->setSubject('Password reset')
            ->setFrom($config['emails']['info'])
            ->setTo($email)
            ->setBody($this->get('templating')->render('MaximCMSBundle:email:forgotpass.html.twig', array('company' => $config['server']['name'], "message" => $message)))
            ->setContentType("text/html")
        ;
        $this->get('mailer')->send($mail);

        return new Response(json_encode(array("success" => true, 'message' => 'E-mail sent')));
    }
    public function validEmail($email)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("email" => $email));

        if (!$user) {
            //throw $this->createNotFoundException('Incorrect key, email verification failed: '.$email[0]);
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public function validUsername($username)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("username" => strtoupper($username)));

        return (!$user);
    }
    public function forgotPassAction($key)
    {
        $security = $this->get('security.helper');
        $config = $this->container->getParameter('maxim_cms');

        $string = $security->decrypt($key, $config['forgotPassword']['salt']);
        $email = explode(':', $string);

        if(!(($email[1] + $config['forgotPassword']['time'])  >= time()))
        {
            $result = array("success" => false, "message" => "Url expired");
            return $this->render('MaximCMSBundle:Modules:account/forgotpassView.html.twig', $result);
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("email" => $email[0]));

        if (!$user) {
            //throw $this->createNotFoundException('Incorrect key, email verification failed: '.$email[0]);

            $result = array("success" => false, "message" => "Incorrect key, email verification failed");
            return $this->render('MaximCMSBundle:Modules:account/forgotpassView.html.twig', $result);
        }

        $factory = $this->get('security.encoder_factory');

        $password = $this->generatePassword();

        $user->setPassword($password);

        $encoder  = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($encodedPassword);

        $em->flush();

        $this->get('session')->setFlash('notice', 'Your password has been changed');

        #Send mail
        $message = 'Your new password is: '.$password;

        $message = \Swift_Message::newInstance()
            ->setSubject('New password')
            ->setFrom($config['emails']['info'])
            ->setTo($email[0])
            ->setBody($this->get('templating')->render('MaximCMSBundle:email:forgotpass.html.twig', array('company' => $config['server']['name'], "message" => $message)))
            ->setContentType("text/html")
        ;
        $this->get('mailer')->send($message);
        return $this->redirect($this->generateUrl('home'));
    }
    public function generatePassword()
    {
        return substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$' ) , 0 , 10 );
    }
}