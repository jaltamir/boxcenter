<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Jaltamir\BoxCoreBundle\Entity\Activity;
use Jaltamir\BoxCoreBundle\Entity\Schedule;
use Jaltamir\BoxCoreBundle\Entity\Session;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class ScheduleAdmin extends AbstractAdmin
{
    protected function configureBatchActions($actions)
    {
        unset($actions['delete']);
        return $actions;
    }

    /**
     * @param Schedule $schedule
     */
    public function preRemove($schedule)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');

        foreach ($em->getRepository(Session::class)->findBy(['schedule' => $schedule]) as $session)
        {
            $em->remove($session);
            $em->flush();
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'activity',
                EntityType::class,
                [
                    'class' => Activity::class,
                ]
            )
            ->add(
                'startTime',
                TimeType::class,
                [
                    'label' => 'Start Time',
                ]
            )
            ->add(
                'weekDay',
                ChoiceType::class,
                [
                    'label' => 'Week Day',
                    'multiple' => true,
                    'choices' => $this->getConfigurationPool()->getContainer()->get(
                        'box_core.manager.schedule'
                    )->getWeekDaysAtChoices(),

                ]
            )
            ->add(
                'active',
                ChoiceType::class,
                [
                    'choices' => [
                        'Inactive' => 0,
                        'Active' => 1,
                    ],
                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('activity')
            ->add(
                'active',
                null,
                [],
                ChoiceType::class,
                [
                    'choices' => [
                        'Inactive' => 0,
                        'Active' => 1,
                    ],
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ID')
            ->add('activity')
            ->add(
                'startTime',
                null,
                [
                    'label' => 'Start Time',
                ]
            )
            ->add(
                'weekDay',
                'array',
                [
                    'label' => 'Week Day',
                    'template' => 'BoxAdminBundle:ScheduleAdmin:list_week_day.html.twig',
                ]
            )
            ->add(
                'active',
                null,
                [],
                ChoiceType::class,
                [
                    'choices' => [
                        'Inactive' => 0,
                        'Active' => 1,
                    ],
                ]
            );
    }
}