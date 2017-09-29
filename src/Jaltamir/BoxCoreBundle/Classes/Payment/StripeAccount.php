<?php

namespace Jaltamir\BoxCoreBundle\Classes\Payment;

use Jaltamir\BoxCoreBundle\Interfaces\Payment\PaymentAccountInterface;

/**
 * @author    Javier Rodríguez <dev@jaltamir.com>
 */
class StripeAccount implements PaymentAccountInterface
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @param string $secretKey
     * @param string $publicKey
     */
    public function __construct($secretKey, $publicKey)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }
}
