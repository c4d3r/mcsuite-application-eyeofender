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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

        if ($exception instanceof HttpExceptionInterface)
        {
            $this->logger->info(sprintf(
                '[ERROR]: %s with code: %s (file: %s, line: %s)',
                $exception->getMessage(),
                $exception->getCode(),
                $exception->getFile(),
                $exception->getLine()
            ));

        }
       /* // You get the exception object from the received event
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
        $event->setResponse($response);       */
    }
}