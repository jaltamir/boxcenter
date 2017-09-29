<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Jaltamir\BoxCoreBundle\Entity\Session;
use Jaltamir\BoxCoreBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AssistanceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'user',
                EntityType::class,
                [
                    'class' => User::class,
                    'label' => 'Select user:',
                ]
            )
            ->add(
                'session',
                EntityType::class,
                [
                    'class' => Session::class,
                    'label' => 'Select pass:',
                ]
            )
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('user');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ID')
            ->addIdentifier('user')
            ->addIdentifier('session')
            ;
    }
}