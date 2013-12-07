<?php
namespace Maxim\CMSBundle\Controller;

use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class ExceptionController extends Controller
{
    /**
     * Converts an Exception to a Response.
     *
     * @param FlattenException     $exception A FlattenException instance
     * @param DebugLoggerInterface $logger    A DebugLoggerInterface instance
     * @param string               $format    The format to use for rendering (html, xml, ...)
     * @param Boolean              $embedded  Whether the rendered Response will be embedded or not
     *
     * @throws \InvalidArgumentException When the exception template does not exist
     */
    public function exceptionAction(FlattenException $exception, DebugLoggerInterface $logger = null, $format = 'html', $embedded = false)
    {
        return $this->render('MaximCMSBundle:Exception:error.html.twig',
                array(
                    'exception' => $exception,
                )
        );
    }

    public function indexAction($match)
    {
        return $this->render('MaximCMSBundle:Exception:404.html.twig');
    }
}