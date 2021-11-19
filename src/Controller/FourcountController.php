<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Balance;
use App\Service\ExportCsvService;
use App\Entity\Fourcount;
use App\Form\FourcountType;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\FourcountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
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
    public function show(Fourcount $fourcount, Balance $balance, ChartBuilderInterface $chartBuilder): Response
    {
        $balance->initArray($fourcount->getParticipants());        
        $balance_array = $balance->setBalance($fourcount->getExpenses());
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Sales!',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(0, 0, 0)',
                    'data' => [522, 1500, 2250, 2197, 2345, 3122, 3099],
                ],
            ],
        ]);        
        return $this->render('fourcount/show.html.twig', [
            'fourcount' => $fourcount,
            'balance' => $balance_array,
            'chart' => $chart,
        ]);
    }

    /**
     * @Route("/{id}/balance/download", name="balance_download")
     */
    public function downloadBalenceCsv(ExportCsvService $exportCsvService, Fourcount $fourcount, Balance $balance ): Response
    {
        $balance->initArray($fourcount->getParticipants());        
        $balance_array = $balance->setBalance($fourcount->getExpenses());   
        return $exportCsvService->createBalanceCsv($balance_array);
        
    }
    /**
     * @Route("/{id}/expenses/download", name="expenses_download")
     */
    public function downloadExpenseCsv(ExportCsvService $exportCsvService, $id ): Response
    {
        $expenses = $this->getDoctrine()->getRepository(Fourcount::class)->find($id)->getExpenses();
        return $exportCsvService->createExpensesCsv($expenses);
        
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

            return $this->redirectToRoute('fourcount_show', ['id' => $fourcount->getId()], Response::HTTP_SEE_OTHER);
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
