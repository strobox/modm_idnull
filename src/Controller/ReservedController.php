<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Document\Customer;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;


class ReservedController extends AbstractController
{

    private $dm;
    private $request;

    public function __construct( DocumentManager $dm, RequestStack $request)
    {
        $this->dm = $dm;
        $this->request = $request;
    }

    /**
     * @Route("/test/odm", name="test_route")
     */
    public function number(DocumentManager $dm)
    {
        $id = $this->request->getCurrentRequest()->query->get('customer_id');
        $dm->find(Customer::class,$id);
        return new Response(
            '<html><body>OK</body></html>'
        );
    }

}