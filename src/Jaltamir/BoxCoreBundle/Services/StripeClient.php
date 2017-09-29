<?php

namespace Jaltamir\BoxCoreBundle\Services;

use JMS\DiExtraBundle\Annotation as DI;
use Jaltamir\BoxCoreBundle\Interfaces\Payment\PaymentAccountInterface;
use Jaltamir\BoxCoreBundle\Interfaces\Payment\PaymentClientInterface;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Refund;
use Stripe\Stripe;

/**
 * @DI\Service("app.stripe_client")
 *
 * @author    Javier Rodríguez <dev@jaltamir.com>
 */
class StripeClient implements PaymentClientInterface
{
    const REFUND_REASON_REQUESTED_BY_CUSTOMER = 'requested_by_customer';

    /**
     * @param PaymentAccountInterface $account
     * @param int $amount
     * @param string $token
     * @param string $description
     * @param array $options
     *
     * @return Charge
     */
    public function charge(PaymentAccountInterface $account, $amount, $token, $description, array $options = []): Charge
    {
        Stripe::setApiKey($account->getSecretKey());

        $chargeParams = [
            'amount' => $amount,
            'description' => $description,
            'currency' => isset($options['currency']) ? $options['currency'] : 'eur',
        ];

        if (isset($options['customerToken'])) {
            $chargeParams['customer'] = $options['customerToken'];
        } else {
            $chargeParams['source'] = $token;
        }

        return Charge::create($chargeParams);
    }

    /**
     * @param PaymentAccountInterface $account
     * @param int $amount
     * @param string $token
     * @param string $description
     *
     * @throws \InvalidArgumentException
     *
     * @return Refund
     */
    public function refund(PaymentAccountInterface $account, $amount, $token, $description = null): Refund
    {
        Stripe::setApiKey($account->getSecretKey());

        if ($description !== null && !$this->isValidReason($description)) {
            throw new \InvalidArgumentException('The reason received is not valid');
        } else {
            $description = self::REFUND_REASON_REQUESTED_BY_CUSTOMER;
        }

        return Refund::create(
            [
                'charge' => $token,
                'amount' => $amount,
                'reason' => $description,
            ]
        );
    }

    /**
     * @param PaymentAccountInterface $account
     * @param string $description
     * @param string $source
     *
     * @return Customer
     */
    public function createCustomer(PaymentAccountInterface $account, $description, $source)
    {
        Stripe::setApiKey($account->getSecretKey());

        return Customer::create(
            [
                'description' => $description,
                'source' => $source,
            ]
        );
    }
}
