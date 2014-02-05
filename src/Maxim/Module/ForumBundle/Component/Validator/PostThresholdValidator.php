<?php
/**
 * Author: Maxim
 * Date: 30/01/14
 * Time: 21:28
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Component\Validator;

use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Entity\User;
use Maxim\CMSBundle\Helper\MinecraftHelper;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class PostThresholdValidator extends ConstraintValidator
{
    private $securityContext;
    private $em;
    private $threshold;

    public function __construct(SecurityContext $securityContext, EntityManager $em, $threshold)
    {
        $this->securityContext = $securityContext;
        $this->em = $em;
        $this->threshold = $threshold;
    }

    public function validate($object, Constraint $constraint)
    {
        # get user
        $user = $this->securityContext->getToken()->getUser();

        # get last thread of the user
        $lastPost = $this->em->getRepository("MaximModuleForumBundle:Post")->findLatestPost($user);

        if(isset($lastPost[0]) && (false === $this->securityContext->isGranted('ROLE_STAFF')))
        {
            // check time difference
            $diff = $lastPost[0]->getCreatedOn()->diff(new \DateTime("now"));

            if(!($diff->days > 0 || $diff->i > $this->threshold))
            {
                $this->context->addViolation($constraint->postThresholdMessage, array(
                    '%time%' => $this->threshold,
                    '%string_minutes%' => $this->threshold > 1 ? "minutes" : "minute")
                );
            }
        }
    }

    public function validatedBy()
    {
        return 'maxim_module_forum_validator_postThreshold';
    }
} 