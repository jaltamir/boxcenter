<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Jaltamir\BoxCoreBundle\Entity\Activity;
use Jaltamir\BoxCoreBundle\Entity\Schedule;
use Jaltamir\BoxCoreBundle\Entity\Session;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActivityAdmin extends AbstractAdmin
{
    protected function configureBatchActions($actions)
    {
        unset($actions['delete']);
        return $actions;
    }

    /**
     * @param Activity $activity
     */
    public function preRemove($activity)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');

        foreach ($em->getRepository(Schedule::class)->findBy(['activity' => $activity]) as $schedule)
        {
            foreach ($em->getRepository(Session::class)->findBy(['schedule' => $schedule]) as $session)
            {
                $em->remove($session);
                $em->flush();
            }

            $em->remove($schedule);
            $em->flush();
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class)
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'Description',
                ]
            )
            ->add(
                'capacity',
                IntegerType::class,
                [
                    'label' => 'Maximum Capacity',
                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name')
            ->add('description');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('ID')
            ->add('name')
            ->add(
                'capacity',
                null,
                [
                    'label' => 'Maximum capacity',
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'Description',
                ]
            );
    }
}