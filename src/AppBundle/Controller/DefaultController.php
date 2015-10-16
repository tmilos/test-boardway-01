<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Command\SignupForBusinessCommand;
use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EmailAddress;
use AppBundle\Domain\Model\EncodedPassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home.index")
     * @Template("default/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', 'email')
            ->add('password', 'password')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $this->get('broadway.command_handling.command_bus')->dispatch(
                new SignupForBusinessCommand(
                    new AccountId($this->get('broadway.uuid.generator')->generate()),
                    new EmailAddress($data['email']),
                    EncodedPassword::encode($this->get('security.encoder_factory')->getEncoder(User::class), $data['password'])
                )
            );

            return $this->redirectToRoute('home.verification_mail_sent');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/verification-mail-sent", name="home.verification_mail_sent")
     * @Template("default/verification_mail_sent.html.twig")
     */
    public function verificationMailSentAction()
    {
        return [];
    }
}
