<?php

namespace Jaltamir\BoxCoreBundle\Interfaces\Payment;

interface PaymentClientInterface
{
    /**
     * @param PaymentAccountInterface $account
     * @param int $amount
     * @param string $token
     * @param string $description
     * @param array $options
     *
     * @return mixed
     */
    public function charge(PaymentAccountInterface $account, $amount, $token, $description, array $options = []);

    /**
     * @param PaymentAccountInterface $account
     * @param int $amount
     * @param string $token
     * @param string $description
     *
     * @return mixed
     */
    public function refund(PaymentAccountInterface $account, $amount, $token, $description);

}
