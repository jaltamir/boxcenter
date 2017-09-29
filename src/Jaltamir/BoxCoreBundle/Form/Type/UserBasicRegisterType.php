<?php

namespace Jaltamir\BoxCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class UserBasicRegisterType extends AbstractType
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
            'email',
            EmailType::class,
            [
                'label' => 'Escribe tu correo electrónico'
            ]
        );

        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'invalid_message' => 'Las contraseñas deben coincidir.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Escribe tu contraseña'],
                'second_options' => ['label' => 'Repite tu contraseña'],
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Registrar',
            ]
        );

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_basic_register_type';
    }
}