<?php

namespace App\AdminBundle\Service;

/**
 * Class MailerService
 * @package App\AdminBundle\Service
 */
class MailerService
{
    private $mailer;
    /** @var  \Twig_Environment */
    private $twig;
    private $transport;
    private $container;

    public function __construct($container, \Swift_Mailer $mailer, $twig, $transport)
    {
        $this->mailer    = $mailer;
        $this->twig      = $twig;
        $this->transport = $transport;
        $this->container = $container;
    }

    public function sendEmail(array $options = null)
    {
        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($options['path']);
        $subject  = trim($template->renderBlock('subject', $options));
        $text     = trim($template->renderBlock('text', $options));
        $html     = trim($template->renderBlock('html', $options));
        $config   = $this->container->get('doctrine')->getRepository('AppClientBundle:Config')->findOneBy([]);

        $fromEmail = !empty($config) && $config->getDefaultEmail() ? $config->getDefaultEmail() : 'no-reply@loadingdeck.com';
        $fromName  = !empty($config) && $config->getBusinessName() ? $config->getBusinessName() : 'Loading Deck';

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(array('no-reply@loadingdeck.com' => 'no-reply@loadingdeck.com'))
            ->setTo($options['client']->getEmail())
            ->setReplyTo(array($fromEmail => $fromName))
        ;

        if (!empty($html)) {
            $message->setBody($html, 'text/html');
        } else {
            $message->setBody($text);
        }

        if(isset($options['additionalContacts']) && is_array($options['additionalContacts'])){
            foreach($options['additionalContacts'] as $additionalContact) $message->addCC($additionalContact);
        }

        if(isset($options['bcc'])) $message->addBcc($options['bcc']);

        $this->mailer->send($message);
    }

    public function flushQueue()
    {
        $spool = $this->mailer->getTransport()->getSpool();
        $spool->flushQueue($this->transport);
    }
}