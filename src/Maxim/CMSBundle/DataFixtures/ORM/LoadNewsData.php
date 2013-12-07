<?php
/**
 * Author: Maxim
 * Date: 01/11/13
 * Time: 11:06
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Maxim\CMSBundle\Entity\News;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        # news post
        $news = new News();
        $news->setUser($this->getReference('c4d3r-user'));
        $news->setContent("Welcome to your first website");
        $news->setWebsite($this->container->getParameter("maxim_cms.website"));

        $manager->persist($news);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

}