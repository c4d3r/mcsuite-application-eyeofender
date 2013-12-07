<?php
/**
 * Project: MCSuite
 * File: AdminPollController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:57
 */

namespace Maxim\AdminBundle\Controller;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Entity\Pollquestion;
use Maxim\CMSBundle\Entity\Pollanswer;
use Maxim\CMSBundle\Entity\Pollresult;

class AdminPollController extends Controller{

    public function pollAction()
    {
        //SELECT COUNT(*) as amount FROM tblpollresults WHERE ResultAnswerId = :id
        $em = $this->getDoctrine()->getManager();
        $qbQ = $em->createQueryBuilder()
            ->select('q.question, q.date, q.id, q.active')
            ->from('MaximCMSBundle:Pollquestion', 'q')
            ->add('orderBy', 'q.date DESC')
            ->setMaxResults(10);

        $result_questions = $qbQ->getQuery()->getResult();
        //GET ANS
        //SELECT pollanswer.id, COUNT(pollresult.answer_id) as amount
        //FROM pollanswer
        //LEFT JOIN pollresult ON pollanswer.id = pollresult.answer_id
        //GROUP BY answer
        $qbA = $em->createQueryBuilder()
            ->select('a.id, a.answer, COUNT(r.answer) as amount, q.id as question_id')
            ->from('MaximCMSBundle:Pollanswer', 'a')
            ->leftJoin('MaximCMSBundle:Pollresult', 'r', 'WITH', 'a.id = r.answer')
            ->innerJoin('MaximCMSBundle:Pollquestion', 'q', 'WITH', 'a.questionId = q.id')
            ->orderBy('q.id')
            ->groupBy('a.answer');

        $qbT = $em->createQueryBuilder()
            ->select('q.id, a.id, a.answer, COUNT(r.answer) as amount, q.id as question_id')
            ->from('MaximCMSBundle:Pollanswer', 'a')
            ->leftJoin('MaximCMSBundle:Pollresult', 'r', 'WITH', 'a.id = r.answer')
            ->innerJoin('MaximCMSBundle:Pollquestion', 'q', 'WITH', 'a.questionId = q.id')
            ->orderBy('q.id')
            ->groupBy('q.id');

        $result_answers = $qbA->getQuery()->getResult();
        $total 			= $qbT->getQuery()->getResult();

        //print_r(array_merge($result_answers, $total));
        $data['result_questions']		= $result_questions;
        $data['result_answers']			= $result_answers;
        //print_r($total);
        $percentage = array();
        $totalVotes = array();

        foreach($result_questions as $key => $result_question)
        {
            foreach($result_answers as $result_answer)
            {
                if($result_answer['question_id'] == $result_question['id'])
                {
                    $percentage[] = ($result_answer['amount'] / $total[$key]['amount'] * 100);
                }

            }
            $totalVotes[$key] = $total[$key]['amount'];
        }
        $data['percentage'] = $percentage;
        $data['total'] = $totalVotes;

        return $this->render('MaximCMSBundle:admin:poll/index.html.twig', $data);
    }

    public function pollAddAction()
    {
        $request = $this->getRequest();
        $logger = $this->container->get('logger');

        if ($request->isXmlHttpRequest()) {
            try
            {
                $em = $this->getDoctrine()->getManager();

                $question 	= $request->request->get('_poll_question');
                $answers 	= $request->request->get('_poll_answers');
                $answers 	= explode(',', $answers);

                $pollquestion = new Pollquestion();
                $pollquestion->setQuestion($question);
                $pollquestion->setDate(new \DateTime("now"));
                $pollquestion->setActive(1);

                $em->persist($pollquestion);
                $em->flush();

                foreach($answers as $answer)
                {
                    $pollanswer = new Pollanswer();
                    $pollanswer->setAnswer($answer);
                    $pollanswer->setQuestionid($pollquestion);

                    $em->persist($pollanswer);
                    $em->flush();
                }
                $logger->info("POLL: Added new poll");
                $output = array("success" => true, "message" => "Your poll has been added succesfully");
            }
            catch(\Exception $ex)
            {
                $output = array("success" => false, "message" => "An error has occured while adding the poll, please report to the website administrator");
                $logger->err("POLL: Error adding new poll: ".$ex->getMessage());
            }
        }
        else
        {
            $logger->err("POLL: Got none POST request");
            $output = array("success" => false, "message" => "not a post request");
        }
        return new Response(json_encode($output));
    }
    public function pollSaveAction()
    {
        $request = $this->getRequest();
        $logger = $this->container->get('logger');

        if ($request->isXmlHttpRequest()) {

            try
            {
                //GET ID and ACTIVE
                $id 	= $request->request->get('_poll_id');
                $active = $request->request->get('_poll_active');

                $active = (($active == "true")) ? 1 : 0;
                $em = $this->getDoctrine()->getManager();

                $repository   = $this->getDoctrine()->getRepository('MaximCMSBundle:Pollquestion');
                $pollquestion = $repository->findOneBy(array('id' => $id));

                if($pollquestion)
                {
                    $pollquestion->setActive($active);
                    $em->flush();

                    $output = array("success" => true, "message" => "Poll question has been changed succesfuly active: ".$active);
                }
                else {
                    $output = array("success" => false,  "message" => "Could not find the poll question");
                }
            }
            catch(\Exception $ex)
            {
                $output = array("success" => false, "message" => "An error has occured while saving the poll, please report to the website administrator");
                $logger->err("POLL: Error saving poll: ".$ex->getMessage());
            }
        }
        else
        {
            $logger->err("POLL: Got none POST request");
            $output = array("success" => false, "message" => "not a post request");
        }

        return new Response(json_encode($output));

    }
}