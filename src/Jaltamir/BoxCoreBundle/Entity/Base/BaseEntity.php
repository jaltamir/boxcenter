<?php

namespace Jaltamir\BoxCoreBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class BaseEntity
{
    /**
     * @ORM\Column(name="created_datetime",type="datetime")
     * @var \DateTime
     */
    protected $createdDatetime;

    /**
     * @ORM\Column(name="updated_datetime", type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $updatedDatetime;

    public function __construct()
    {
        $this->createdDatetime = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedDatetime = new \DateTime();
    }

    /**
     *
     * @return \DateTime
     */
    public function getUpdatedDatetime()
    {
        return $this->updatedDatetime;
    }

    /**
     *
     * @return \DateTime
     */
    public function getCreatedDatetime()
    {
        return clone $this->createdDatetime;
    }
}
