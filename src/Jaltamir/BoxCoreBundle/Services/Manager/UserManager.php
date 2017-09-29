<?php

namespace Jaltamir\BoxCoreBundle\Services\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMInvalidArgumentException;
use Jaltamir\BoxCoreBundle\Entity\User;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @DI\Service("box_core.manager.user")
 *
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @DI\InjectParams({
     *     "em"                  = @DI\Inject("doctrine.orm.entity_manager"),
     *     "validator"           = @DI\Inject("validator"),
     *     "userPasswordEncoder" = @DI\Inject("security.password_encoder"),
     * })
     *
     * @param EntityManager $em
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(EntityManager $em, ValidatorInterface $validator, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->em                   = $em;
        $this->validator            = $validator;
        $this->userPasswordEncoder  = $userPasswordEncoder;
    }

    /**
     * @param User $user
     * @param string $passwordRaw
     *
     * @return User
     *
     * @throws \InvalidArgumentException
     * @throws ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \OutOfBoundsException
     */
    public function registerUser(User $user, string $passwordRaw)
    {
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $passwordRaw));
        $errors = $this->validator->validate($user);

        if (count($errors) > 0)
        {
            $violation = $errors->get(0);
            throw new \InvalidArgumentException($violation->getMessage());
        }

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @param User $user
     *
     * @throws \InvalidArgumentException
     * @throws ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \OutOfBoundsException
     */
    public function updateUser(User $user)
    {
        $errors = $this->validator->validate($user);

        if (count($errors) > 0)
        {
            $violation = $errors->get(0);
            throw new \InvalidArgumentException($violation->getMessage());
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}
