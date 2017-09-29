<?php

namespace Jaltamir\BoxCoreBundle\Services;

use Jaltamir\BoxCoreBundle\Entity\User;
use JMS\DiExtraBundle\Annotation as DI;
use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @DI\Service("box_core.manager.email")
 *
 */
class MailerManager
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * @var string
     */
    private $apiKey = '173b20a0c8e867be666e24502ea8e164';

    /**
     * @var string
     */
    private $apiSecret = '0f861a50f435d0fd3c01095209502141';

    /**
     * @DI\InjectParams({
     *     "templating"    = @DI\Inject("templating"),
     *     "translator"    = @DI\Inject("translator.default"),
     * })
     *
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * @param User $user
     */
    public function sendMailWelcome(User $user)
    {
        $html = $this->templating->render('@Ucafit/Templates/Email/welcome_user.html.twig', ['user' => $user]);

        $mj = new Client($this->apiKey, $this->apiSecret);

        $body = [
            'FromEmail' => "noreply@ucafit.net",
            'FromName' => "UCAFit",
            'Subject' => $this->translator->trans('¡Bienvenido a UCAFit!'),
            'Html-part' => $html,
            'Recipients' => [['Email' => $user->getEmail()]],
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }
}
