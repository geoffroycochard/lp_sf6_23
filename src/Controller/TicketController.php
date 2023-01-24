<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    #[Route('/ticket', name: 'app_ticket')]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    #[Route('/ticket/{id}', name: 'app_ticket_edit')]
    public function edit(
        Ticket $ticket, 
        Request $request,
        TicketRepository $ticketRepository
    )
    {
        $form = $this->createForm(
            TicketType::class,
            $ticket
        );
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $ticketRepository->save($ticket, true);
            return $this->redirectToRoute('app_ticket');
        }

        return $this->render('ticket/edit.html.twig', [
            'form' => $form
        ]);
    }
}
