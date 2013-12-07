<?php
namespace Maxim\CMSBundle\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\DependencyInjection\ContainerAware;
class AuthenticationHandler extends ContainerAware
implements AuthenticationSuccessHandlerInterface,
           AuthenticationFailureHandlerInterface
{
    private $templating;

    public function __construct($templating)
    {
        $this->templating = $templating;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            $result = array('message' => 'logged in', 'success' => true);

            /* UPDATE USER RECORD */
            $user = $token->getUser();
            $user->setLastLogin(new \DateTime("now"));
            $user->setLastip($_SERVER['REMOTE_ADDR']);
            $this->container->get('doctrine')->getManager()->flush();

            return new Response(json_encode($result));
        } else {
            // Handle non XmlHttp request here
            return $this->templating->render('MaximCMSBundle:Exception:400.html.twig');
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            $result = array('message' => 'Incorrect username or password', 'success' => false);
            return new Response(json_encode($result));
        } else {
            // Handle non XmlHttp request here
            return $this->templating->render('MaximCMSBundle:Exception:400.html.twig');
        }
    }
}
