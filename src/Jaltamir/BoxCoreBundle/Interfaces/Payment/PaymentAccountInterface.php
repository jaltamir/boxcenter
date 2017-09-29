<?php

namespace Jaltamir\BoxCoreBundle\Interfaces\Payment;


interface PaymentAccountInterface
{
    /**
     * @return string
     */
    public function getSecretKey();

    /**
     * @return string
     */
    public function getPublicKey();
}
