<?php
/**
 * Author: Maxim
 * Date: 13/11/13
 * Time: 19:39
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Maxim\CMSBundle\Exception\ModuleException;

class VoteController extends Controller
{
    public function loginAction(Request $request)
    {

    }

    public function obtainVoteSitesAction(Request $request)
    {

    }

    public function voteAction($siteid)
    {
        $request = $this->getRequest();

    }

    public function renderAction()
    {
        $em = $this->getDoctrine()->getManager();

        # get all sites
        $query = $em->createQuery(
            "SELECT v FROM MaximCMSBundle:Vote v"
        );
        $votingsites = $query->getResult();

        # get sites voted on during the past time
      /*  $user = $this->getUser();
        $query = $em->createQuery(
            "SELECT uv
            FROM MaximCMSBundle:UserVote uv
            JOIN uv.user u
            JOIN uv.site v
            WHERE u.id = :userid
            AND UNIX_TIMESTAMP(uv.votedOn) >= (UNIX_TIMESTAMP() - v.reset)"
        )->setParameter('userid', $user->getId());

        $sitesVotedOn = $query->getResult();

        $sites = array();
        foreach($votingsites as $site)
        {
            if(!in_array($site['id'], $sitesVotedOn))
            {
                $sites[] = $site;
            }
        }


        if(!$sites) {
            throw new ModuleException("Could not load module: voting");
        }*/

        $data['votingsites'] = $votingsites;
        return $this->render('MaximCMSBundle:Module:vote/vote.html.twig', $data);
    }
}