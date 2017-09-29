<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;

/**
 * @ApiResource()
 *
 * @ORM\Entity(repositoryClass="Jaltamir\BoxCoreBundle\Repository\AssistanceRepository")
 * @ORM\Table()
 *
 */
class Assistance extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\Session")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var Session
     */
    private $session;

    /**
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var User
     */
    private $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCancellable()
    {
        if (!$this->session instanceof Session)
        {
            return false;
        }

        $sessionDatetime = $this->session->getStartDateTime();

        $limit = new \DateTime('now');
        $limit->modify('+ 30 minutes');

        return $limit < $sessionDatetime;
    }

    /**
     * @return string
     */
    public function _toString()
    {
        $string = sprintf('%s %s %s',
            $this->user,
            $this->session->getSchedule()->getActivity()->getDescription(),
            $this->session->getStartDateTimeFormat()
            );

        return $string;
    }

}