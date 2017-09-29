<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PassAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                ]
            )
            ->add(
                'desc',
                TextType::class,
                [
                    'label' => 'Description',
                ]
            )
            ->add(
                'numSessions',
                IntegerType::class,
                [
                    'label' => 'Sessions/month',

                ]
            )
            ->add(
                'price',
                NumberType::class,
                [
                    'label' => 'Price/month',
                    'scale' => 2,

                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'name',
                null,
                [
                    'label' => 'Name',
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ID')
            ->add(
                'name',
                null,
                [
                    'label' => 'Name',
                ]
            )
            ->add(
                'desc',
                null,
                [
                    'label' => 'Description',
                ]
            )
            ->add(
                'numSessions',
                IntegerType::class,
                [
                    'label' => 'Sessions/month',

                ]
            )
            ->add(
                'price',
                NumberType::class,
                [
                    'label' => 'Price/month',
                    'scale' => 2,

                ]
            );
    }
}