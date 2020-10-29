<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods={"GET"})
     * @param PinRepository $pinRepository
     * @return Response
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findby([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pin/create", name="app_pins_create", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws LogicException
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pin = new Pin();
        $form = $this->createForm(PinType::class, $pin);

//        Verification des donnees du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($pin);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show", methods={"GET"})
     * @param Pin $pin
     * @return Response
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @param Request $request
     * @param Pin $pin
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws LogicException
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT"})
     */
    public function edit(Request $request, Pin $pin, EntityManagerInterface $entityManager): Response
    {
//        $pin = new Pin();
        $form = $this->createForm(PinType::class, $pin, [
            'method' => 'PUT'
        ]);

//        Verification des donnees du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render("pins/edit.html.twig", [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param Pin $pin
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('pin_deletion'.$pin->getId(), $request->request->get('csrf_token')))
        {
            $entityManager->remove($pin);
            $entityManager->flush();
        } 
        return $this->redirectToRoute('app_home');
    }
}
