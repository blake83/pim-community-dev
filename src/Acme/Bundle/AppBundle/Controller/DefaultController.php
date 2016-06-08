<?php

namespace Acme\Bundle\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/acme/{product}", name="acme_index")
     * @ParamConverter("product", class="Pim\Component\Catalog\Model\Product")
     */
    public function indexAction($product)
    {
        $serializer = $this->container->get('pim_serializer');
        $data = $serializer->serialize($product, 'json');

//        var_dump($data);
//        die();

//        return new Response();
        return new Response(print_r($data, true));
    }
}
