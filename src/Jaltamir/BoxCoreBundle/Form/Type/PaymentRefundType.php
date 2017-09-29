<?php

namespace Jaltamir\BoxCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentRefundType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'payment',
            HiddenType::class,
            [
                'data' => $options['payment'],
            ]
        );

        $builder->add(
            'user',
            HiddenType::class,
            [
                'data' => $options['user'],
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => $options['manualPayment'] !== true? 'Refund payment' : 'Cancel payment',
                'attr' => [
                    'class' => $options['manualPayment'] !== true? 'btn btn-danger' : 'btn btn-warning'
                ]
            ]
        );

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payment_refund_type';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'user' => null,
                'payment' => null,
                'manualPayment' => null,
            ]
        );
    }
}
