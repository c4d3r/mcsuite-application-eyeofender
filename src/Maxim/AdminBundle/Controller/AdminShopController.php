<?php
/**
 * Project: MCSuite
 * File: AdminShopController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:50
 */

namespace Maxim\AdminBundle\Controller;
use Maxim\CMSBundle\Entity\Shop;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;


class AdminShopController extends Controller{

    public function shopAction()
    {
        return $this->render('MaximAdminBundle:Shop:view.html.twig');
    }

    public function shopAddViewAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $sections = $em->getRepository('MaximCMSBundle:Section')->findAll();
        $websites = $em->getRepository('MaximCMSBundle:Website')->findAll();

        $data['servers']  = $this->getDoctrine()->getRepository('MaximCMSBundle:Server')->findAll();
        $data['websites'] = $websites;
        $data['sections'] = $sections;
        return $this->render('MaximAdminBundle:Shop:add.html.twig', $data);
    }
    public function shopEditViewAction($id)
    {
        $em 		 = $this->getDoctrine()->getManager();
        $repository  = $em->getRepository('MaximCMSBundle:Shop');
        $shop = $repository->findOneBy(array("id" => $id));
        $sections = $em->getRepository('MaximCMSBundle:Section')->findAll();
        $websites = $em->getRepository('MaximCMSBundle:Website')->findAll();

        $data['websites'] = $websites;
        $data['sections'] = $sections;
        $data['servers']  = $this->getDoctrine()->getRepository('MaximCMSBundle:Server')->findAll();
        $data['shop'] = $shop;
        return $this->render('MaximAdminBundle:Shop:edit.html.twig', $data);
    }

    public function shopSectionAddViewAction()
    {
        return $this->render('MaximAdminBundle:Shop:section/add.html.twig');
    }
    public function shopSectionEditViewAction($id)
    {
        $em 		 = $this->getDoctrine()->getManager();
        $repository  = $em->getRepository('MaximCMSBundle:Section');
        $section = $repository->findOneBy(array("id" => $id));

        $data['section'] = $section;
        return $this->render('MaximAdminBundle:Shop:section/edit.html.twig', $data);
    }

    public function shopListAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Shop');

        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }

    public function shopAddAction()
    {
        $request = $this->getRequest();
        $logger = $this->container->get('logger');

        if ($request->isXmlHttpRequest()) {
            try
            {
                $em = $this->getDoctrine()->getManager();

                $shop_name 			= $request->request->get('_admin_shop_name');
                $shop_description 	= $request->request->get('_admin_shop_description');
                $shop_amount 		= $request->request->get('_admin_shop_amount');
                $shop_visible 		= $request->request->get('_admin_shop_visible');
                $shop_command 		= $request->request->get('_admin_shop_command');
                $shop_image 		= $request->request->get('_admin_shop_image');
                $shop_reduction 	= $request->request->get('_admin_shop_reduction');
                $shop_section       = $request->request->get('_admin_shop_section');
                $shop_server        = $request->request->get('_admin_shop_server');
                $shop_priority      = $request->request->get('_admin_shop_priority');
                $shop_website       = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $request->request->get('_admin_shop_website')));

                $shop = new Shop();
                $shop->setName($shop_name);
                $shop->setDescription($shop_description);
                $shop->setAmount($shop_amount);
                $shop->setVisible(($shop_visible == true) ? 1 : 0);
                $shop->setCommand($shop_command);
                $shop->setImage($shop_image);
                $shop->setReduction($shop_reduction);
                $shop->setServer($em->getRepository('MaximCMSBundle:Server')->findOneBy(array("id" => $shop_server)));
                $shop->setWebsite($shop_website);

                if(isset($shop_priority))
                {
                    $shop->setPriority($shop_priority);
                }
                //get section
                $section = $em->getRepository('MaximCMSBundle:Section')->findOneBy(array("id" => $shop_section));
                if($section)
                {
                    $shop->setSection($section);
                }
                else
                {
                    $output = array("success" => false, "message" => "An error has occured while adding the item: section not found, id: ".$shop_section.", please report to the website administrator");
                }
                $em->persist($shop);
                $em->flush();

                $logger->info("SHOP: Added new item");
                $output = array("success" => true, "message" => "Your item has been added succesfully");
            }
            catch(\Exception $ex)
            {
                $output = array("success" => false, "message" => "An error has occured while adding the item, please report to the website administrator");
                $logger->err("SHOP: Error adding new item: ".$ex->getMessage());
            }
        }
        else
        {
            $logger->err("SHOP: Got none POST request");
            $output = array("success" => false, "message" => "not a post request");
        }
        return new Response(json_encode($output));
    }
    public function shopEditAction()
    {
        $request = $this->getRequest();
        $logger = $this->container->get('logger');

        if ($request->isXmlHttpRequest()) {
            try
            {
                $em = $this->getDoctrine()->getManager();

                $shop_name 			= $request->request->get('_admin_shop_name');
                $shop_description 	= $request->request->get('_admin_shop_description');
                $shop_amount 		= $request->request->get('_admin_shop_amount');
                $shop_visible 		= $request->request->get('_admin_shop_visible');
                $shop_command 		= $request->request->get('_admin_shop_command');
                $shop_image 		= $request->request->get('_admin_shop_image');
                $shop_reduction 	= $request->request->get('_admin_shop_reduction');
                $shop_section       = $request->request->get('_admin_shop_section');
                $shop_server        = $request->request->get('_admin_shop_server');
                $shop_priority      = $request->request->get('_admin_shop_priority');
                $shop_website       = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $request->request->get('_admin_shop_website')));

                #GET EXISTING ITEM
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Shop');
                $shop = $repository->findOneBy(array('id' => $request->request->get('_admin_shop_id')));

                $shop->setName($shop_name);
                $shop->setDescription($shop_description);
                $shop->setAmount($shop_amount);
                $shop->setVisible(($shop_visible == true) ? 1 : 0);
                $shop->setCommand($shop_command);
                $shop->setImage($shop_image);
                $shop->setReduction($shop_reduction);
                $shop->setServer($em->getRepository('MaximCMSBundle:Server')->findOneBy(array("id" => $shop_server)));
                $shop->setWebsite($shop_website);

                if(isset($shop_priority))
                {
                    $shop->setPriority($shop_priority);
                }
                //get section
                $section = $em->getRepository('MaximCMSBundle:Section')->findOneBy(array("id" => $shop_section));
                if($section)
                {
                    $shop->setSection($section);
                }
                else
                {
                    $output = array("success" => false, "message" => "An error has occured while adding the item: section not found, id: ".$shop_section.", please report to the website administrator");
                }

                $em->persist($shop);
                $em->flush();

                $logger->info("SHOP: edited item");
                $output = array("success" => true, "message" => "Your item has been edited succesfully");
            }
            catch(\Exception $ex)
            {
                $output = array("success" => false, "message" => "An error has occured while editing the item, please report to the website administrator");
                $logger->err("SHOP: Error editing item: ".$ex->getMessage());
            }
        }
        else
        {
            $logger->err("SHOP: Got none POST request");
            $output = array("success" => false, "message" => "not a post request");
        }
        return new Response(json_encode($output));
    }
    public function shopDeleteAction($id)
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Shop');
                $shop = $repository->findOneBy(array('id' => $id));

                if($shop)
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($shop);
                    $em->flush();
                    $result = array("success" => true, "message" => "Item deleted succesfuly");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error deleting Item, Item with id:".$id." could not be found");
                }
            }
            catch(\Exception $ex)
            {
                $result  = array("success" => false, "message" => "Error deleting Item: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }

    #shop sections
    public function shopSectionListAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Section');

        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }
    public function shopSectionAddAction()
    {
        $request = $this->getRequest();
        $logger = $this->container->get('logger');

        if ($request->isXmlHttpRequest()) {
            try
            {
                $em = $this->getDoctrine()->getManager();

                $section_name 			= $request->request->get('_admin_section_name');
                $section_description 	= $request->request->get('_admin_section_description');

                $section = new Section();
                $section->setName($section_name);
                $section->setDescription($section_description);


                $em->persist($section);
                $em->flush();

                $logger->info("SHOP SECTION: Added new section");
                $output = array("success" => true, "message" => "Your section has been added succesfully");
            }
            catch(\Exception $ex)
            {
                $output = array("success" => false, "message" => "An error has occured while adding the section, please report to the website administrator");
                $logger->err("SHOP SECTION: Error adding new section: ".$ex->getMessage());
            }
        }
        else
        {
            $logger->err("SHOP: Got none POST request");
            $output = array("success" => false, "message" => "not a post request");
        }
        return new Response(json_encode($output));
    }
    public function shopSectionEditAction()
    {
        $request = $this->getRequest();
        $logger = $this->container->get('logger');

        if ($request->isXmlHttpRequest()) {
            try
            {
                $em = $this->getDoctrine()->getManager();

                $section_name 			= $request->request->get('_admin_section_name');
                $section_description 	= $request->request->get('_admin_section_description');

                #GET EXISTING ITEM
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Section');
                $section = $repository->findOneBy(array('id' => $request->request->get('_admin_section_id')));

                $section->setName($section_name);
                $section->setDescription($section_description);

                $em->persist($section);
                $em->flush();

                $logger->info("SHOP SECTION: edited item");
                $output = array("success" => true, "message" => "Your section has been edited succesfully");
            }
            catch(\Exception $ex)
            {
                $output = array("success" => false, "message" => "An error has occured while editing the section, please report to the website administrator");
                $logger->err("SHOP SECTION: Error editing section: ".$ex->getMessage());
            }
        }
        else
        {
            $logger->err("SHOP SECTION: Got none POST request");
            $output = array("success" => false, "message" => "not a post request");
        }
        return new Response(json_encode($output));
    }
    public function shopSectionDeleteAction($id)
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Section');
                $section = $repository->findOneBy(array('id' => $id));

                if($section)
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($section);
                    $em->flush();
                    $result = array("success" => true, "message" => "Section deleted succesfuly");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error deleting Section, Section with id:".$request->request->get('_admin_section_id')." could not be found");
                }
            }
            catch(\Exception $ex)
            {
                $result  = array("success" => false, "message" => "Error deleting Section: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }
}