<?php

namespace App\Controller;

use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @param PinRepository $pinRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(PinRepository $pinRepository)
    {
        $pins = $pinRepository->findAll();
        return $this->render('pins/index.html.twig', compact('pins'));
    }
}
