<?php
/**
 * Author: Maxim
 * Date: 01/11/13
 * Time: 10:59
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Maxim\CMSBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface{

    public function load(ObjectManager $manager)
    {
        $userC4D3R = new User();
        $userC4D3R->setEmail("c4d3r@hotmail.com");
        $userC4D3R->setUsername("c4d3r");
        $userC4D3R->setPassword("1839a056396a1385bc387dfa2973c632722f0346");

        $userC4D3R->addRank($this->getReference('rank-admin'));

        $manager->persist($userC4D3R);
        $manager->flush();

        $this->addReference('c4d3r-user', $userC4D3R);
    }

    public function getOrder()
    {
        return 2;
    }

}