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
use Maxim\CMSBundle\Entity\Group;

class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface{

    public function load(ObjectManager $manager)
    {
        $groupAdmin = new Group();
        $groupAdmin->setName("Admin");
        $groupAdmin->setRoleName("ROLE_ADMIN");
        $groupAdmin->setDescription("The admin group");

        $manager->persist($groupAdmin);

        $group = new Group();
        $group->setName("Member");
        $group->setRoleName("ROLE_MEMBER");
        $group->setDescription("The member group");
        $group->setDefault(true);

        $manager->persist($group);
        $manager->flush();

        $this->addReference('group-admin', $groupAdmin);
    }


    public function getOrder()
    {
        return 1;
    }

}