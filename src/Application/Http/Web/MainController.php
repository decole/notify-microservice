<?php


namespace App\Application\Http\Web;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function number(): Response
    {
        return $this->render('welcome.html.twig');
    }
}