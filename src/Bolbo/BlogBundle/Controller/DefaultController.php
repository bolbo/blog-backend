<?php

namespace Bolbo\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BolboBlogBundle:Default:index.html.twig', array('name' => $name));
    }
}
