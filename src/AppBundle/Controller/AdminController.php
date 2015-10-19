<?php

namespace AppBundle\Controller;

use AppBundle\Domain\Model\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/{companyId}")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin.index")
     * @Template("admin/index.html.twig")
     */
    public function indexAction($companyId)
    {
        // temporary view, until read model projects are done

        /** @var Company $company */
        $company = $this->get('domain.repository.company')->load($companyId);

        return [
            'companyDomain' => $company->getDomain(),
        ];
    }
}
