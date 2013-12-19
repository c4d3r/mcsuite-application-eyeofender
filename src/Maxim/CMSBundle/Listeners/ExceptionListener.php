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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
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
            '[ERROR]: %s with code: %s (file: %s, line: %s)',
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine()
        );

        $this->logger->err($message);
        // Customize your response object to display the exception details
        $response = new Response();

        if ($exception instanceof NotFoundHttpException) {
            return $this->templating->render('MaximCMSBundle:Exception:404.html.twig');
        }
        if($exception instanceof AuthenticationCredentialsNotFoundException)
        {
            return $this->templating->render('MaximCMSBundle:Exception:404.html.twig');
        }

        if($exception instanceof AccessDeniedException)
            return $this->render("MaximCMSBundle:Exception:AccessDenied.html.twig");


        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface)
        {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }
        else
        {
            $response->setStatusCode(500);
        }
        // Send the modified response object to the event
        $event->setResponse($response);
    }
}