<?php
/**
 * Author: Maxim
 * Date: 01/11/13
 * Time: 11:10
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Maxim\CMSBundle\Entity\Rank;

class LoadRankData extends AbstractFixture implements OrderedFixtureInterface{

    public function load(ObjectManager $manager)
    {
        $rankAdmin = new Rank();
        $rankAdmin->setName("Admin");
        $rankAdmin->setRoleName("ROLE_ADMIN");
        $rankAdmin->setDescription("The admin rank");

        $manager->persist($rankAdmin);

        $rank = new Rank();
        $rank->setName("Member");
        $rank->setRoleName("ROLE_MEMBER");
        $rank->setDescription("The member rank");
        $rank->setDefault(true);

        $manager->persist($rank);
        $manager->flush();

        $this->addReference('rank-admin', $rankAdmin);
    }


    public function getOrder()
    {
        return 1;
    }

}