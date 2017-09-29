<?php

namespace Jaltamir\BoxCoreBundle\Factory;

use JMS\DiExtraBundle\Annotation as DI;
use Jaltamir\BoxCoreBundle\Classes\Payment\StripeAccount;

/**
 * @DI\Service("app.stripe_account.factory")
 *
 */
class StripeAccountFactory
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
     * @DI\InjectParams({
     *     "secretKey"        = @DI\Inject("%stripe.secret_key%"),
     *     "publicKey"        = @DI\Inject("%stripe.public_key%"),
     * })
     *
     * @param string $secretKey
     * @param string $publicKey
     */
    public function __construct($secretKey, $publicKey)
    {
        $this->publicKey        = $publicKey;
        $this->secretKey        = $secretKey;
    }

    /**
     *
     * @return StripeAccount
     */
    public function createStripeAccount()
    {
        return new StripeAccount($this->secretKey, $this->publicKey);
    }
}
