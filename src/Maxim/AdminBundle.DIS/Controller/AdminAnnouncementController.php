<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 15/09/13
 * Time: 16:48
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\AdminBundle\Controller;


use LanKit\DatatablesBundle\Datatables\Datatable;
use Maxim\CMSBundle\Entity\Announcement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAnnouncementController extends Controller{

    public function announcementsAction()
    {
        return $this->render('MaximAdminBundle:Announcement:index.html.twig');
    }

    public function announcementAddViewAction()
    {
        $websites = $this->getDoctrine()->getRepository('MaximCMSBundle:Website')->findAll();
        $data['websites'] = $websites;
        return $this->render('MaximAdminBundle:Announcement:add.html.twig', $data);
    }

    public function announcementAddAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        $text = $request->request->get('_announcement_text');
        $type = $request->request->get('_announcement_type');
        $website = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $request->request->get('_announcement_website')));
        $startdate = $request->request->get('_announcement_startdate');
        $enddate = $request->request->get('_announcement_enddate');



        if(!$website){
            return new Response(json_encode(array("success" => false, "message" => "Could not find the requested website")));
        }

        if(empty($startdate)) {
            $startdate = new \DateTime(date('Y-m-d H:i:s'));
        } else {
            $startdate = new \DateTime($startdate);
        }

        try {
            $a = new Announcement();
            $a->setText($text);
            $a->setType($type);
            $a->setStartdate($startdate);
            $a->setEnddate(new \DateTime($enddate));
            $a->setUser($this->getUser());
            $a->setWebsite($website);
            $em->persist($a);
            $em->flush();
        }catch(\Exception $ex) {
            $logger->err("error" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error has occured when adding your announcement, please contact your web administrator or try again later")));
        }

        return new Response(json_encode(array("success" => true, "message" => "Your announcement has been added succesfully")));
    }

    public function announcementListAction() {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Announcement');
        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }

    public function announcementDeleteAction($id)
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Announcement');
            $announcement = $repository->findOneById($id);
            if($announcement)
            {
                $em = $this->getDoctrine()->getManager();
                $em->remove($announcement);
                $em->flush();
                $result = array("success" => true, "message" => "announcement deleted succesfuly");
            }
            else
            {
                $result = array("success" => false, "message" => "Error deleting announcement");
            }

            return new Response(json_encode($result));
        }
    }
    public function announcementView($id)
    {
        return new Response('');
    }


}