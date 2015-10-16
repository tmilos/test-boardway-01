<?php

namespace AppBundle\Infra\Mailer;

class TwigMailer 
{
    /** @var  \Swift_Mailer */
    protected $mailer;

    /** @var  \Twig_Environment */
    protected $twig;

    /** @var  array */
    protected $parameters;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param array $parameters
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    /**
     * @param string $templateName
     * @param array $context
     * @param string $toEmail
     * @param string $toName
     * @param string|null $fromEmail
     * @param string|null $fromName
     * @return int
     */
    public function sendTwigMessage($templateName, array $context, $toEmail, $toName, $fromEmail = null, $fromName = null)
    {
        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);
        $subject = trim($template->renderBlock('subject', $context));
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->hasBlock('body_html') ? $template->renderBlock('body_html', $context) : '';

        return $this->sendMessage($subject, $textBody, $htmlBody, $toEmail, $toName, $fromEmail, $fromName);
    }

    /**
     * @param string $subject
     * @param string $textBody
     * @param string $htmlBody
     * @param string $toEmail
     * @param string $toName
     * @param string|null $fromEmail
     * @param string|null $fromName
     * @return int
     */
    public function sendMessage($subject, $textBody, $htmlBody, $toEmail, $toName, $fromEmail = null, $fromName = null)
    {
        if (!$fromEmail) {
            $fromEmail = $this->parameters['from_email'];
        }
        if (!$fromName) {
            $fromName = $this->parameters['from_name'];
        }

        if ('' == trim($toName)) {
            $toName = $toEmail;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail, $fromName)
            ->setTo($toEmail, $toName);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html');
            $message->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $sent = $this->mailer->send($message);

        return $sent;
    }
}
