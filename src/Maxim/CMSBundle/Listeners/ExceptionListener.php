<?php
/**
 * Project: MCSuite
 * File: ExceptionListener.php
 * User: Maxim
 * Date: 26/04/13
 * Time: 21:50
 */

namespace Maxim\CMSBundle\Listeners;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
class ExceptionListener {

    protected $logger;

    public function __construct($logger, $templating, $security)
    {
        $this->logger = $logger;
        $this->templating = $templating;
        $this->security = $security;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        // You get the exception object from the received event
        $exception = $event->getException();

        $message = sprintf(
            '[ERROR]: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        $this->logger->err($message);
        // Customize your response object to display the exception details
        $response = new Response();

        if($exception instanceof AccessDeniedHttpException)
        {
            $request = $event->getRequest();
            $_route  = $request->getPathInfo();
            $path = explode('/', substr($_route, 1));

            if(strtolower(trim($path[0])) == "admin" && ($this->security->isGranted("ROLE_ADMIN") || $this->security->isGranted("ROLE_STAFF")))
            {
                $response->setContent($this->templating->render('MaximAdminBundle:Exception:forbidden.html.twig'));
            }
            else
            {
                $response->setContent($this->templating->render('MaximCMSBundle:Exception:404.html.twig'));
            }
        }
        else
        {
            $response->setContent($this->templating->render('MaximCMSBundle:Exception:404.html.twig'));
        }


        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(500);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}