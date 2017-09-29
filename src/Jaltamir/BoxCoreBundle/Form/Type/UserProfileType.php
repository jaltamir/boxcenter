<?php

namespace Jaltamir\BoxCoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserProfileType extends AbstractType
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
            'name',
            TextType::class,
            [
                'label' => 'Nombre',
                'required' => true
            ]
        );

        $builder->add(
            'surname',
            TextType::class,
            [
                'label' => 'Apellidos',
                'required' => true
            ]
        );
        $builder->add(
            'nif',
            TextType::class,
            [
                'label' => 'NIF',
                'required' => true
            ]
        );

        $builder->add(
            'address',
            TextType::class,
            [
                'label' => 'Dirección',
            ]
        );

        $builder->add(
            'phone',
            TextType::class,
            [
                'label' => 'Teléfono',
            ]
        );

        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Actualizar perfil',
                'attr' => [
                    'class' => 'btn btn-danger btn-update-profile'
                ]
            ]
        );

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_profile_type';
    }
}