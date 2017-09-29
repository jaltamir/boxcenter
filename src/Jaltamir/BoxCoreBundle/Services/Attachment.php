<?php

namespace Jaltamir\BoxCoreBundle\Services;

use JMS\DiExtraBundle\Annotation as DI;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @DI\Service("box_core.attachment")
 *
 */
class Attachment
{
    /**
     * @var LoggableGenerator
     */
    private $snappy;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @DI\InjectParams({
     *     "snappy"     = @DI\Inject("knp_snappy.pdf"),
     *     "templating" = @DI\Inject("templating"),
     * })
     *
     * @param LoggableGenerator $snappy
     * @param EngineInterface   $templating
     */
    public function __construct(LoggableGenerator $snappy, EngineInterface $templating)
    {
        $this->snappy     = $snappy;
        $this->templating = $templating;
    }

    /**
     * @param $html
     * @param $path
     */
    private function generatePdf($html, $path)
    {
        $this->snappy->generateFromHtml(
            $html,
            $path,
            [
                'margin-left'  => 10,
                'margin-right' => 10,
                'page-size'    => 'A4',
                'zoom'         => '1',
            ],
            true
        );
    }
}
