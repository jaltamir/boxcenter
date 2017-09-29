<?php

namespace Jaltamir\BoxAdminBundle\Admin;

use Jaltamir\BoxCoreBundle\Event\SessionsViewEvent;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SessionAdmin extends AbstractAdmin
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('id')
            ->add(
                'startDateTime',
                'doctrine_orm_date_range',
                [
                    'label' => 'Session Dates',
                    'field_type' => 'sonata_type_datetime_range_picker',
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $event = new SessionsViewEvent();
        $this->eventDispatcher->dispatch(SessionsViewEvent::NAME, $event);

        $listMapper->addIdentifier('ID')
            ->add(
                'schedule',
                EntityType::class,
                [
                    'label' => 'Activity',
                ]
            )
            ->add('startDateTimeFormat', null, ['label' => 'Date and Time']);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }

}