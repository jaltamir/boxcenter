<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;

/**
 *
 * @ApiResource()
 *
 * @ORM\Entity(repositoryClass="Jaltamir\BoxCoreBundle\Repository\PaymentRepository")
 * @ORM\Table()
 */
class Payment extends BaseEntity
{
    const PAYMENT_STATE_CONFIRM = 2;
    const PAYMENT_STATE_CANCELLED = 1;
    const PAYMENT_STATE_PENDING = 0;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var User
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\Pass")
     * @ORM\JoinColumn(name="pass_id", referencedColumnName="id", nullable=false)
     *
     * @var Pass
     */
    private $pass;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $comments;

    /**
     * @ORM\Column(type="integer", nullable=false)
     *
     * @var integer
     */
    private $state;

    /**
     * @ORM\Column(type="float", nullable=false)
     *
     * @var float
     */
    private $netPrice;

    /**
     * @ORM\Column(type="float", nullable=false)
     *
     * @var float
     */
    private $vatPrice;

    /**
     * @ORM\Column(type="float", nullable=false)
     *
     * @var float
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     *
     * @var array
     */
    private $response;

    /**
     * @ORM\Column(name="date_subscribed", type="datetime", nullable=false)
     * @var \DateTime
     */
    private $dateSubscribed;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     *
     * @var bool
     */
    private $isManuallyCreated;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
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
     *
     * @return Pass
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param Pass $pass
     * @return $this
     */
    public function setPass(Pass $pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * @return string
     */
    public function getComments(): string
    {
        return (string)$this->comments;
    }

    /**
     * @param $comments
     * @return $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return integer
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param integer $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return float
     */
    public function getNetPrice(): float
    {
        return $this->netPrice;
    }

    /**
     * @param float $netPrice
     * @return $this
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = $netPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getVatPrice(): float
    {
        return $this->vatPrice;
    }

    /**
     * @param float $vatPrice
     * @return $this
     */
    public function setVatPrice($vatPrice)
    {
        $this->vatPrice = $vatPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    /**
     * @param float $totalPrice
     * @return $this
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function setResponse(array $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateSubscribed()
    {
        return $this->dateSubscribed;
    }

    /**
     * @param \DateTime $dateSubscribed
     * @return $this
     */
    public function setDateSubscribed(\DateTime $dateSubscribed)
    {
        $this->dateSubscribed = $dateSubscribed;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStripeToken()
    {
        $token = null;
        if (is_array($this->response) && isset($this->response['id']))
        {
            $token = $this->response['id'];
        }

        return $token;
    }

    /**
     * @return bool
     */
    public function isManuallyCreated(): bool
    {
        return $this->isManuallyCreated;
    }

    /**
     * @param bool $isManuallyCreated
     */
    public function setIsManuallyCreated(bool $isManuallyCreated)
    {
        $this->isManuallyCreated = $isManuallyCreated;
    }
}