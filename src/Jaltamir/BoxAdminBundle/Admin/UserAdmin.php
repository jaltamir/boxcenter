<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Jaltamir\BoxCoreBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdmin extends AbstractAdmin
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param mixed $user
     */
    public function prePersist($user)
    {
        $passwordEncoded = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($passwordEncoded);
    }

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function setPasswordEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add(
                'nif',
                TextType::class,
                [
                    'label' => 'NIF',
                ]
            );

        if (!$this->getSubject() instanceof User || $this->getSubject()->getId() === null) {
            $formMapper->add(
                'password',
                PasswordType::class,
                [
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ]
            );
        }

        $formMapper
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'required' => false,
                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add(
            'name',
            null,
            [
                'label' => $this->trans('Nombre'),
            ]
        )
            ->add(
                'surname',
                null,
                [
                    'label' => $this->trans('Apellidos'),
                ]
            )
            ->add('email')
            ->add('nif');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ID')
            ->add(
                'name',
                null,
                [
                    'label' => $this->trans('Nombre'),
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => $this->trans('Apellidos'),
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => $this->trans('Email'),
                ]
            )
            ->add(
                'nif',
                null,
                [
                    'label' => 'NIF',
                ]
            )
            ->add(
                'flagAdmin',
                null,
                [
                    'label' => $this->trans('User Type'),
                    'template' => 'BoxAdminBundle:UserAdmin:flag_admin.html.twig',
                ]
            )
            ->add(
                '_action',
                'actions',
                [
                    'label' => $this->trans('Acciones'),
                    'actions' => [
                        'change_password' => [
                            'template' => 'BoxAdminBundle:UserAdmin:list_action_change_password.html.twig',
                        ],
                    ],
                ]
            );
    }
}