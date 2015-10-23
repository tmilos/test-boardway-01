<?php

namespace AppBundle\Controller;

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
        $company = $this->get('read_model.repository.company')->find($companyId);
        $employees = $this->get('read_model.repository.employee')->findBy(['companyId'=>$companyId]);

        return [
            'company' => $company,
            'employees' => $employees,
        ];
    }
}
