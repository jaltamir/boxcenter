<?php

namespace Jaltamir\BoxCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
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
            'user',
            HiddenType::class,
            [
                'data' => $options['user'],
            ]
        );

        $builder->add(
            'pass',
            HiddenType::class,
            [
                'data' => $options['pass'],
            ]
        );

        $builder->add(
            'month',
            HiddenType::class,
            [
                'data' => $options['month'],
            ]
        );

        $builder->add(
            'productToken',
            HiddenType::class,
            [
                'data' => $options['productToken'],
            ]
        );


        $builder->add(
            'stripePublicKey',
            HiddenType::class,
            [
                'data' => $options['stripePublicKey'],
            ]
        );

        $builder->add(
            'stripeToken',
            HiddenType::class,
            [
                'data' => null,
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => $options['priceDesc'],
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'user' => null,
                'stripePublicKey' => null,
                'pass' => null,
                'month' => null,
                'date' => null,
                'productToken' => null,
                'priceDesc' => null,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}



