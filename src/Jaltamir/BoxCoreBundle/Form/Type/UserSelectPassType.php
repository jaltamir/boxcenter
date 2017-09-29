<?php

namespace Jaltamir\BoxCoreBundle\Form\Type;

use Jaltamir\BoxCoreBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSelectPassType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['user'];

        $builder->add(
            'pass',
            ChoiceType::class,
            [
                'label' => 'Seleccione el abono que deseas contratar:',
                'choices' => $options['passChoices'],
            ]
        );

        $builder->add(
            'month',
            DateType::class,
            [
                'widget' => 'single_text',
                'format' => 'MM-YYYY',
                'label' => 'Seleccione el mes:',
            ]
        );

        $builder->add(
            'user',
            HiddenType::class,
            [
                'data' => $user->getId(),
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Ir al Pago',
            ]
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            [$this, 'onPreSubmit']
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
                'passChoices' => null,
                'passSelected' => null,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_select_pass_type';
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSubmit(FormEvent $event)
    {
        $userData = $event->getData();
        $month = null;

        if (isset($userData['month']))
        {
            $month = new \DateTime('01-'.$userData['month']);
        }

        $userData['month'] = $month;
        $event->setData($userData);
    }
}



