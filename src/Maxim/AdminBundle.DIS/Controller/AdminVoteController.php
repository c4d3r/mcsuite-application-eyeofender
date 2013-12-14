<?php
/**
 * Project: MCSuite
 * File: AdminVoteController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:53
 */

namespace Maxim\AdminBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Entity\Vote;

class AdminVoteController extends Controller{

    public function voteAction()
    {
        return $this->render('MaximCMSBundle:admin:vote.html.twig', array("error" => NULL));
    }

    public function voteAddAction()
    {
        $request = $this->getRequest();

        $vote 		 	= new Vote();
        $vote_id 		= $request->request->get('_admin_vote_id');
        $vote_name 		= $request->request->get('_admin_vote_name');
        $vote_link		= $request->request->get('_admin_vote_link');
        $vote_image 	= $request->request->get('_admin_vote_image');
        $vote_reset 	= $request->request->get('_admin_vote_reset');
        $vote_website 	= $request->request->get('_admin_vote_website');
        $vote_votifier 	= $request->request->get('_admin_vote_votifier');
        $vote_server    = $request->request->get('_admin_vote_server');

        $vote->setName($vote_name);
        $vote->setLink($vote_link);
        $vote->setImage($vote_image);
        $vote->setReset($vote_reset);
        $vote->setWebsite($vote_website);
        $vote->setVotifier($vote_votifier);
        $vote->setServer($this->getDoctrine()->getRepository('MaximCMSBundle:Server')->findOneBy(array("id" => $vote_server)));

        $em = $this->getDoctrine()->getManager();
        $em->persist($vote);
        $em->flush();

        $result = array('success' => true, 'message' => 'vote added');

        return new Response(json_encode($result));
    }

    public function voteDeleteAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Vote');
                $user = $repository->findOneBy(array('id' => $request->request->get('_admin_vote_id')));

                if($user)
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($user);
                    $em->flush();
                    $result = array("success" => true, "message" => "Vote deleted succesfuly");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error deleting Vote");
                }
            }
            catch(\Exception $ex)
            {
                $result = $result = array("success" => false, "message" => "Error deleting Vote: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }
    public function voteUpdateAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            $repository = $this->getDoctrine()->getRepository('MaximCMSBundle:Vote');

            $vote_id 		= $request->request->get('_admin_vote_id');
            $vote_name 		= $request->request->get('_admin_vote_name');
            $vote_link		= $request->request->get('_admin_vote_link');
            $vote_image 	= $request->request->get('_admin_vote_image');
            $vote_reset 	= $request->request->get('_admin_vote_reset');
            $vote_website 	= $request->request->get('_admin_vote_website');
            $vote_votifier 	= $request->request->get('_admin_vote_votifier');
            $vote_server    = $request->request->get('_admin_vote_server');

            $vote = $repository->findOneById($vote_id);

            if($vote)
            {
                $em = $this->getDoctrine()->getManager();

                $vote->setName($vote_name);
                $vote->setLink($vote_link);
                $vote->setImage($vote_image);
                $vote->setReset($vote_reset);
                $vote->setWebsite($vote_website);
                $vote->setVotifier($vote_votifier);
                $vote->setServer($em->getRepository('MaximCMSBundle:Server')->findOneBy(array("id" => $vote_server)));

                $em->flush();
                $result = array("success" => true, "message" => "vote edited succesfuly");
            }
            else
            {
                $result = array("success" => false, "message" => "Error editing vote: Vote with id ".$vote_id." could not be found");
            }

            return new Response(json_encode($result));
        }
    }

    public function voteListAction() {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Vote');
        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }

}