<?php

namespace Jaltamir\BoxCoreBundle\Services\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Jaltamir\BoxCoreBundle\Entity\Assistance;
use Jaltamir\BoxCoreBundle\Entity\Schedule;
use Jaltamir\BoxCoreBundle\Entity\Session;
use Jaltamir\BoxCoreBundle\Factory\SessionFactory;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class SessionManager
{
    const SCHEDULE_FIRST_HOUR = '08:00';
    const SCHEDULE_LAST_HOUR = '23:00';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionFactory
     */
    private $sessionFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     *
     * @param EntityManagerInterface $em
     * @param SessionFactory $sessionFactory
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $em, SessionFactory $sessionFactory, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->sessionFactory = $sessionFactory;
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     */
    public function generateSessions(ArrayCollection $schedules, \DateTime $from, \DateTime $to)
    {
        $sessions = new ArrayCollection();

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($from, $interval, $to);

        foreach ($period as $day) {

            foreach ($schedules as $schedule) {

                /** @var Schedule $schedule */
                foreach ($schedule->getWeekDay() as $weekDay) {

                    if ($day->format('l') === $weekDay) {

                        $sessionStart = new \DateTime(
                            $day->format('Y-m-d').' '.$schedule->getStartTime()->format('H:i')
                        );

                        $sessions->add($this->createOrLoadSession($schedule, $sessionStart));
                    }
                }
            }
        }
    }

    /**
     * @param Schedule $schedule
     * @param \DateTime $startSession
     * @return Session|null|object
     */
    private function createOrLoadSession(Schedule $schedule, \DateTime $startSession)
    {
        $sessionRepository = $this->em->getRepository(Session::class);
        $session = $sessionRepository->findOneBy(
            [
                'schedule' => $schedule,
                'startDateTime' => $startSession,
            ]
        );

        if (!$session instanceof Session) {
            $session = $this->sessionFactory->create($schedule, $startSession);
        }

        $this->em->persist($session);
        $this->em->flush();

        return $session;
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return array
     */
    public function getSessionsAsOrderedHash(\DateTime $from, \DateTime $to): array
    {
        $results = [];
        $sessionsRepository = $this->em->getRepository(Session::class);

        $sessions = $sessionsRepository->findBetweenDates($from, $to);

        $daysInterval = \DateInterval::createFromDateString('1 day');
        $days = new \DatePeriod($from, $daysInterval, $to);

        foreach ($days as $day) {

            $firstHour = new \DateTime($day->format('Y-m-d').' '.self::SCHEDULE_FIRST_HOUR);
            $lastHour = new \DateTime($day->format('Y-m-d').' '.self::SCHEDULE_LAST_HOUR);

            $hoursInterval = \DateInterval::createFromDateString('1 hour');
            $hours = new \DatePeriod($firstHour, $hoursInterval, $lastHour);

            list($dayNumber, $dateTitle) = explode(' ', ucfirst(strftime('%u %d-%m', $day->getTimestamp())));
            $dayTitle = $this->getDayOfWeek($dayNumber);
            $results['days_ordered'][] = [
                'dayTitle' => $dayTitle,
                'dateTitle' => $dateTitle,
            ];

            foreach ($hours as $hourBegin) {

                $hourEnd = clone $hourBegin;
                $hourEnd->modify('+1 hours');

                $hourKey = $hourBegin->format('H:i');
                $results['sessions_ordered'][$hourKey][$dayTitle] = [];
                foreach ($sessions as $session) {
                    /** @var Session $session */

                    if ($session->getStartDateTime() >= $hourBegin && $session->getStartDateTime() < $hourEnd) {
                        $results['sessions_ordered'][$hourKey][$dayTitle][] = [
                            'start_time' => $session->getStartDateTime()->format('H:i'),
                            'start_date' => $session->getStartDateTime()->format('Y-m-d'),
                            'start_date_located' => $session->getStartDateTime()->format('d-m-Y'),
                            'id' => $session->getId(),
                            'booking_url' => $this->router->generate('booking', ['id' => $session->getId()]),
                            'activity_name' => $session->getSchedule()->getActivity()->getName(),
                            'capacity' => $session->getSchedule()->getActivity()->getCapacity(),
                            'bookings' => $this->em->getRepository(Assistance::class)->countAssistances($session)
                        ];
                    }
                }
            }
        }

        return $results;
    }

    /**
     * @param $dayWeekNumber
     * @return string
     */
    private function getDayOfWeek($dayWeekNumber)
    {
        switch ($dayWeekNumber)
        {
            case 1:
                $day = $this->translator->trans('Lunes');
                break;

            case 2:
                $day = $this->translator->trans('Martes');
                break;

            case 3:
                $day = $this->translator->trans('Miércoles');
                break;

            case 4:
                $day = $this->translator->trans('Jueves');
                break;

            case 5:
                $day = $this->translator->trans('Viernes');
                break;

            case 6:
                $day = $this->translator->trans('Sábado');
                break;

            default:
                $day = $this->translator->trans('Domingo');
                break;
        }

        return $day;
    }
}

