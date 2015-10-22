<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Command\SignupForBusinessCommand;
use AppBundle\Domain\Command\VerifyBusinessCommand;
use AppBundle\Domain\Model\CompanyId;
use AppBundle\Domain\Model\EmailAddress;
use AppBundle\Domain\Model\EncodedPassword;
use AppBundle\Security\User\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/login", name="home.login")
     * @Template("default/login.html.twig")
     */
    public function loginAction()
    {
        $form = $this->createFormBuilder()
            ->add('username', 'email')
            ->add('password', 'password')
            ->setAction($this->generateUrl('home.login_check'))
            ->getForm();

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/login_check", name="home.login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/verification-mail-sent", name="home.verification_mail_sent")
     * @Template("default/verification_mail_sent.html.twig")
     */
    public function verificationMailSentAction()
    {
        return [];
    }

    /**
     * @Route("/verify/{id}", name="home.verify_business")
     */
    public function verifyBusinessAction($id)
    {
        $this->get('broadway.command_handling.command_bus')->dispatch(new VerifyBusinessCommand(
            new EmailAddress($id),
            $companyId = new CompanyId($this->get('broadway.uuid.generator')->generate())
        ));

        return $this->redirectToRoute('admin.index', ['companyId'=>$companyId->getValue()]);
    }
}
