<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Jaltamir\BoxCoreBundle\Entity\Pass;
use Jaltamir\BoxCoreBundle\Entity\Payment;
use Jaltamir\BoxCoreBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PaymentAdmin extends AbstractAdmin
{
    /**
     * @param Payment $payment
     */
    public function prePersist($payment)
    {
        /** @var \DateTime $dateSubscribed */
        $dateSubscribed = $this->getForm()['dateSubscribed']->getNormData();
        $dateSubscribed->setDate($dateSubscribed->format('Y'), $dateSubscribed->format('m'), 1);

        $pass = $payment->getPass();

        $payment->setState(Payment::PAYMENT_STATE_CONFIRM)
            ->setDateSubscribed($dateSubscribed)
            ->setNetPrice(round((float)$pass->getPrice()*0.79, 2))
            ->setVatPrice(round((float)$pass->getPrice()*0.21, 2))
            ->setTotalPrice($pass->getPrice())
            ->setIsManuallyCreated(true);
    }

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
                'pass',
                EntityType::class,
                [
                    'class' => Pass::class,
                    'label' => 'Select pass:',
                ]
            )
            ->add(
                'dateSubscribed',
                'sonata_type_date_picker',
                [
                    'widget' => 'single_text',
//                    'format' => 'MM-YYYY',
                    'label' => 'Select the subscription beginning:',
                    'dp_min_date' => (new \DateTime('first day this month'))->format('d-m-Y'),
                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('pass')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ID')
            ->add(
                'user',
                EntityType::class,
                [
                    'label' => 'User',
                    'class' => User::class
                ]
            )
            ->add(
                'pass',
                EntityType::class,
                [
                    'label' => 'Pass',
                    'class' => Pass::class,
                    'associated_property' => 'name',
                    'to_string_callback' => function($entity, $property) {
                        return $entity->getName();
                    },
                ]
            )
            ->add(
                'dateSubscribed',
                'date',
                [
                    'pattern' => 'MM-YYYY',
                    'label' => 'Month Subscribed',
                ]
            )
            ->add(
                'netPrice',
                NumberType::class,
                [
                    'label' => 'Net Price (€)',
                    'scale' => 2,
                ]
            )
            ->add(
                'vatPrice',
                NumberType::class,
                [
                    'label' => 'Vat (€)',
                    'scale' => 2,
                ]
            )
            ->add(
                'totalPrice',
                NumberType::class,
                [
                    'label' => 'Total Amount (€)',
                    'scale' => 2,
                ]
            )
            ->add(
                'state',
                null,
                [
                    'label' => 'State',
                    'template' => 'BoxAdminBundle:PaymentAdmin:state.html.twig',
                ]
            )
            ->add(
                '_action',
                'actions',
                [
                    'actions' => [
                        'refund' => [
                            'template' => 'BoxAdminBundle:PaymentAdmin:list_action_refund.html.twig',
                        ],
                    ],
                ]
            );;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'create'));
    }
}