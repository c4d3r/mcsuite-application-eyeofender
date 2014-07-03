<?php
/**
 * Author: Maxim
 * Date: 12/06/2014
 * Time: 21:28
 * Property of MCSuite
 */

namespace Maxim\Module\TicketBundle\Controller;

use Maxim\Module\TicketBundle\Entity\TicketHistory;
use Maxim\Module\TicketBundle\Entity\TicketReply;
use Maxim\Module\TicketBundle\Form\Type\ReplyType;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller
{
    public function createAction()
    {
        // the key used to lookup the template
        $templateKey = 'edit';
        $logger = $this->get('logger');
        $request = $this->get('request');

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $object = $this->admin->getNewInstance();

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->getRestMethod()== 'POST') {
            $form->bind($request);

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {

                # set fields
                $tempFields = $request->request->get('_fields');

                # set keys
                $fields = array();
                foreach($tempFields as $field)
                {
                    $fields[] = array("TYPE" => $field['type'], "NAME" => $field['name']);
                }

                $object->setFields($fields);

                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                $this->addFlash('sonata_flash_success','flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', 'flash_create_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
    }

    public function replyAction()
    {
        // the key used to lookup the template
        $templateKey = 'edit';
        $logger = $this->get('logger');
        $request = $this->get('request');

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $object = $this->admin->getNewInstance();

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->getRestMethod()== 'POST') {
            $form->bind($request);

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {

                # set fields
                $tempFields = $request->request->get('_fields');

                # set keys
                $fields = array();
                foreach($tempFields as $field)
                {
                    $fields[] = array("TYPE" => $field['type'], "NAME" => $field['name']);
                }

                $object->setFields($fields);

                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                $this->addFlash('sonata_flash_success','flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash('sonata_flash_error', 'flash_create_error');
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
    }

    public function showAction($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('VIEW', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        # reply form
        $replyForm = $this->createForm(new ReplyType());

        $request = Request::createFromGlobals();

        if ( $request->isMethod( 'POST' ) ) {

            $replyForm->bind( $request );

            if ( $replyForm->isValid( ) ) {

                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $replyForm->getData();

                //create ticket reply
                $em = $this->getDoctrine()->getManager();

                $ticket = $object;
                if(!$ticket) {
                    $response['success'] = false;
                    throw $this->createNotFoundException("Could not find ticket with id: " . $id);
                } else {
                    $tr = new TicketReply($ticket, $data['text'], $this->getUser());
                    $th = new TicketHistory($ticket, TicketHistory::TYPE_REPLY, $this->getUser());
                    $em->persist($tr);
                    $em->persist($th);
                    $em->flush();
                }
            }
        }

        return $this->render("MaximModuleTicketBundle:Admin:ticket_show.html.twig", array(
            'ticket'    => $object,
            'replyform' => $replyForm->createView(),
            'action'    => 'show',
            'object'    => $object,
            'elements'  => $this->admin->getShow(),
        ));
    }
} 