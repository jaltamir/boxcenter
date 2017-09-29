<?php

namespace Jaltamir\BoxAdminBundle\Controller;

use Jaltamir\BoxCoreBundle\Entity\Payment;
use Jaltamir\BoxCoreBundle\Form\Type\PaymentRefundType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends Controller
{
    /**
     * @Route("/refund/payment/{id}/show", name="refund")
     * @Template("@BoxAdmin/PaymentAdmin/refund_payment.html.twig")
     *
     * @param Payment $payment
     * @return array
     */
    public function showRefundPaymentAction(Payment $payment): array
    {
        $form = $this->createForm(
            PaymentRefundType::class,
            null,
            [
                'action' => $this->generateUrl('refund_action', ['id' => $payment->getId()], true),
                'method' => 'POST',
                'user' => $this->getUser()->getId(),
                'payment' => $payment->getId(),
                'manualPayment' => $payment->isManuallyCreated(),
            ]
        );

        return [
            'payment' => $payment,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/refund/payment/{id}/do", name="refund_action")
     * @Method("post")
     *
     * @param Request $request
     * @param Payment $payment
     * @return array
     */
    public function refundPaymentAction(Request $request, Payment $payment)
    {
        $paymentManager = $this->get('box_core.manager.payment');
        $data = $request->get('payment_refund');

        if (!isset($data['payment']) || (int)$data['payment'] !== $payment->getId())
        {
            throw new \InvalidArgumentException('The payment received is not the same as requested');
        }

        $paymentManager->cancelPayment($payment);

        if ($payment->isManuallyCreated() === false)
        {
            try{
                $paymentManager->handleRefund($payment);
                $request->getSession()->getFlashBag()->add(
                    'success',
                    'The payment has been cancelled and refunded.'
                );
            }catch (\Exception $exception){
                $request->getSession()->getFlashBag()->add(
                    'danger',
                    'The payment is cancelled but the refund is not possible. The refund has to be done through Stripe Dashboard.'
                );
            }
        }else{
            $request->getSession()->getFlashBag()->add(
                'success',
                'The payment has been cancelled.'
            );
        }

        return $this->redirectToRoute('admin_jaltamir_boxcore_payment_list');
    }
}
