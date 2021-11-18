<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Entity\Fourcount;
use App\Form\ExpenseType;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/expense")
 */
class ExpenseController extends AbstractController
{
    /**
     * @Route("/", name="expense_index", methods={"GET"})
     */
    public function index(ExpenseRepository $expenseRepository): Response
    {
        return $this->render('expense/index.html.twig', [
            'expenses' => $expenseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{fourcountId}", name="expense_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, $fourcountId): Response
    {
        $expense = new Expense();
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fourcount = $this->getDoctrine()->getRepository(Fourcount::class)->find($fourcountId);
            $expense->setFourcount($fourcount);
            $entityManager->persist($expense);
            $entityManager->flush();
            

            return $this->redirectToRoute('fourcount_show', ['id' => $fourcount->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/new.html.twig', [
            'expense' => $expense,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="expense_show", methods={"GET"})
     */
    public function show(Expense $expense): Response
    {
        return $this->render('expense/show.html.twig', [
            'expense' => $expense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="expense_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Expense $expense, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('expense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('expense/edit.html.twig', [
            'expense' => $expense,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="expense_delete", methods={"POST"})
     */
    public function delete(Request $request, Expense $expense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expense->getId(), $request->request->get('_token'))) {
            $entityManager->remove($expense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expense_index', [], Response::HTTP_SEE_OTHER);
    }
}
