<?php

namespace Jaltamir\BoxCoreBundle\Services\Manager;

use Doctrine\ORM\EntityManager;
use Jaltamir\BoxCoreBundle\Entity\Pass;
use Jaltamir\BoxCoreBundle\Entity\Payment;
use Jaltamir\BoxCoreBundle\Entity\Refund;
use Jaltamir\BoxCoreBundle\Entity\User;
use Jaltamir\BoxCoreBundle\Factory\StripeAccountFactory;
use Jaltamir\BoxCoreBundle\Services\StripeClient;
use JMS\DiExtraBundle\Annotation as DI;
use Stripe\ApiResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @DI\Service("box_core.manager.payment")
 *
 */
class PaymentManager
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
     * @var StripeClient
     */
    private $stripeClient;

    /**
     * @var StripeAccountFactory
     */
    private $stripeAccountFactory;

    /**
     * @DI\InjectParams({
     *     "em"                   = @DI\Inject("doctrine.orm.entity_manager"),
     *     "validator"            = @DI\Inject("validator"),
     *     "stripeClient"         = @DI\Inject("app.stripe_client"),
     *     "stripeAccountFactory" = @DI\Inject("app.stripe_account.factory"),
     * })
     *
     * @param EntityManager $em
     * @param ValidatorInterface $validator
     * @param StripeClient $stripeClient
     * @param StripeAccountFactory $stripeAccountFactory
     */
    public function __construct(
        EntityManager $em,
        ValidatorInterface $validator,
        StripeClient $stripeClient,
        StripeAccountFactory $stripeAccountFactory
    ) {
        $this->em = $em;
        $this->validator = $validator;
        $this->stripeClient = $stripeClient;
        $this->stripeAccountFactory = $stripeAccountFactory;
    }

    /**
     * @param Pass $pass
     * @param User $user
     * @param \DateTime $month
     * @param string $stripeToken
     * @return Payment
     * @throws \Exception
     */
    public function handleCharge(Pass $pass, User $user, \DateTime $month, string $stripeToken)
    {
        $amount = $pass->getPrice();
        $amountFormatted = round($amount, 2) * 100;
        $stripeAccount = $this->stripeAccountFactory->createStripeAccount();
        $payment = new Payment();
        $charge = null;

        $payment->setTotalPrice($amount)
            ->setNetPrice(round((float)$amount * 0.79, 2))
            ->setVatPrice(round((float)$amount * 0.21, 2))
            ->setPass($pass)
            ->setUser($user)
            ->setDateSubscribed($month)
            ->setState(Payment::PAYMENT_STATE_PENDING)
            ->setIsManuallyCreated(false);

        try {
            $paymentDesc = sprintf(
                "%s - %s - %s",
                $user->getEmail(),
                $pass->getNameForFrontShort(),
                $month->format('m-Y')
            );
            $charge = $this->stripeClient->charge($stripeAccount, $amountFormatted, $stripeToken, $paymentDesc);

            /** @var ApiResponse $apiResponse */
            $apiResponse = $charge->getLastResponse();
            $payment->setResponse(json_decode($apiResponse->body, true));
        } catch (\Exception $e) {

            throw $e;
        }

        $payment->setState(Payment::PAYMENT_STATE_CONFIRM);

        $this->em->persist($payment);
        $this->em->flush();

        return $payment;
    }

    /**
     * @param Payment $payment
     */
    public function handleRefund(Payment $payment)
    {
        $amountFormatted = round($payment->getTotalPrice(), 2) * 100;
        $stripeAccount = $this->stripeAccountFactory->createStripeAccount();
        $refund = new Refund();

        $refund->setPayment($payment)
            ->setTotal($payment->getTotalPrice());

        $refundResponse = $this->stripeClient->refund($stripeAccount, $amountFormatted, $payment->getStripeToken());

        $response = $refundResponse->getLastResponse();
        $refund->setResponse(json_decode($response->body, true));

        $this->em->persist($refund);
        $this->em->flush();
    }

    /**
     * @param User $user
     * @param Pass $pass
     * @param \DateTime $month
     * @return string
     */
    public function generateProductToken(User $user, Pass $pass, \DateTime $month)
    {
        return sha1(sprintf("%s-%s-%s", $user->getId(), $pass->getId(), $month->format('Y-m')));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAbleToPay(User $user): bool
    {
        return $user->getNif() !== null && $user->getName() !== null && $user->getSurname() !== null;
    }

    /**
     * @param Payment $payment
     */
    public function cancelPayment(Payment $payment)
    {
        $payment->setState(Payment::PAYMENT_STATE_CANCELLED);

        $this->em->persist($payment);
        $this->em->flush();
    }
}
