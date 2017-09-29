<?php

namespace Jaltamir\BoxCoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Jaltamir\BoxCoreBundle\Entity\Base\BaseEntity;
use Jaltamir\BoxCoreBundle\Entity\Payment;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 *
 * @ORM\Entity()
 * @ORM\Table
 *
 * @author Javier Rodríguez <dev@jaltamir.com>
 */
class Refund extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @var Payment
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Jaltamir\BoxCoreBundle\Entity\Payment")
     */
    private $payment;

    /**
     * @var float
     *
     * @ORM\Column(precision=19, scale=2, type="decimal")
     */
    private $total;

    /**
     * @ORM\Column(type="json_array", nullable=false)
     *
     * @var array
     */
    private $response;

    /**
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     *
     * @return $this
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

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
}
