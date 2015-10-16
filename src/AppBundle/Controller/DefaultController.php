<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Command\SignupForBusinessCommand;
use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EmailAddress;
use AppBundle\Domain\Model\EncodedPassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->get('broadway.command_handling.command_bus')->dispatch(
            new SignupForBusinessCommand(
                new AccountId('123'),
                new EmailAddress('email@domain.com'),
                new EncodedPassword('123123123123', 'asd')
            )
        );

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
