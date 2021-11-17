<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Fourcount;
use App\Form\FourcountType;
use App\Repository\FourcountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/fourcount")
 */
class FourcountController extends AbstractController
{
    /**
     * @Route("/", name="fourcount_index", methods={"GET"})
     */
    public function index(FourcountRepository $fourcountRepository): Response
    {
        return $this->render('fourcount/index.html.twig', [
            'fourcounts' => $fourcountRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fourcount_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fourcount = new Fourcount();
        $users= $this->getDoctrine()->getRepository(User::class)->findAll();
        $form = $this->createForm(FourcountType::class, $fourcount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fourcount);
            $entityManager->flush();

            return $this->redirectToRoute('fourcount_show', ['id' => $fourcount->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fourcount/new.html.twig', [
            'fourcount' => $fourcount,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fourcount_show", methods={"GET"})
     */
    public function show(Fourcount $fourcount): Response
    {
        return $this->render('fourcount/show.html.twig', [
            'fourcount' => $fourcount,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fourcount_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Fourcount $fourcount, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FourcountType::class, $fourcount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('fourcount_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fourcount/edit.html.twig', [
            'fourcount' => $fourcount,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="fourcount_delete", methods={"POST"})
     */
    public function delete(Request $request, Fourcount $fourcount, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fourcount->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fourcount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
