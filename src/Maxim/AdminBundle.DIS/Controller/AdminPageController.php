<?php
/**
 * Project: MCSuite
 * File: AdminPageController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:55
 */

namespace Maxim\AdminBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Entity\Page;
class AdminPageController extends Controller{

    public function pageAction()
    {
        return $this->render('MaximAdminBundle:Pages:view.html.twig');
    }
    public function pageAddViewAction()
    {
        $websites = $this->getDoctrine()->getRepository('MaximCMSBundle:Website')->findAll();
        $data['websites'] = $websites;

        return $this->render('MaximAdminBundle:Pages:add.html.twig', $data);
    }

    public function pageEditAction($id)
    {
        #GET NEWS POS WITH ID
        $em 		= $this->getDoctrine()->getManager();
        $config = Yaml::parse('../src/Maxim/CMSBundle/Resources/config/settings.yml');

        $repository = $em->getRepository('MaximCMSBundle:Page');
        $page		= $repository->findOneBy(array("id" => $id));
        $websites = $this->getDoctrine()->getRepository('MaximCMSBundle:Website')->findAll();

        $data['websites']   = $websites;
        $data['page']       = $page;
        return $this->render('MaximAdminBundle:Pages:edit.html.twig', $data);
    }
    public function pageEditAjaxAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $page_id 		= $request->request->get('_admin_page_id');
        $page_url 		= $request->request->get('_admin_page_url');
        $page_name 		= $request->request->get('_admin_page_name');
        $page_content	= $request->request->get('_admin_page_content');
        $website        = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $request->request->get('_admin_page_website')));

        $repository = $em->getRepository('MaximCMSBundle:Page');
        $page		= $repository->findOneBy(array("id" => $page_id));

        try
        {
            $page->setContent($page_content);
            $page->setUrl($page_url);
            $page->setName($page_name);
            $page->setWebsite($website);

            $em->flush();
            $result = array('success' => true, 'message' => 'page saved');
        }
        catch(\Exception $ex)
        {
            $result = array('success' => false, 'message' => 'Could not edit page, please try again later or contact a web administrator');
        }
        return new Response(json_encode($result));
    }


    public function pageAddAction()
    {
        $request = $this->getRequest();

        $em = $this->getDoctrine()->getManager();

        $page 		 	= new Page();
        $page_url 		= $request->request->get('_admin_page_url');
        $page_name 		= $request->request->get('_admin_page_name');
        $page_content	= $request->request->get('_admin_page_content');
        $website        = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $request->request->get('_admin_page_website')));

        try
        {
            $page->setContent($page_content);
            $page->setUrl($page_url);
            $page->setName($page_name);
            $page->setWebsite($website);

            $em->persist($page);
            $em->flush();
            $result = array('success' => true, 'message' => 'page added');
        }
        catch(\Exception $ex)
        {
            $result = array('success' => false, 'message' => 'Could not add page, please try again later or contact a web administrator');
        }
        return new Response(json_encode($result));
    }

    public function pageDeleteAction($id)
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Page');
                $page = $repository->findOneBy(array('id' => $id));

                if($page)
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($page);
                    $em->flush();
                    $result = array("success" => true, "message" => "Page deleted succesfuly");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error deleting Page, Page with id:".$request->request->get('_admin_page_id')." could not be found");
                }
            }
            catch(\Exception $ex)
            {
                $result  = array("success" => false, "message" => "Error deleting Page: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }

    public function pageListAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Page');
        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }

}