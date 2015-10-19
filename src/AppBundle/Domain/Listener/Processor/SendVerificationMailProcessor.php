<?php

namespace AppBundle\Domain\Listener\Processor;

use AppBundle\Domain\Event\AccountSignedForBusinessEvent;
use AppBundle\Infra\Mailer\TwigMailer;
use Broadway\Processor\Processor;
use Symfony\Component\Routing\Router;

class SendVerificationMailProcessor extends Processor
{
    /** @var TwigMailer */
    private $mailer;

    /** @var Router */
    private $router;

    /**
     * @param TwigMailer $mailer
     * @param Router     $router
     */
    public function __construct(TwigMailer $mailer, Router $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function handleAccountSignedForBusinessEvent(AccountSignedForBusinessEvent $event)
    {
        $this->mailer->sendTwigMessage(
            'mail/verification_mail.html.twig',
            [
                'url'=>$this->router->generate(
                    'home.verify_business',
                    ['id'=>$event->getId()->getValue()],
                    Router::ABSOLUTE_URL
                ),
            ],
            $event->getEmail()->getValue(),
            $event->getEmail()->getValue()
        );
    }
}
