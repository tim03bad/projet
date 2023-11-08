<?php

namespace App\Controller;

use App\Entity\Selection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\SelectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/selection')]
class SelectionController extends AbstractController
{
    
    #[Route('/', name: 'selection_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $selections=[];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if the "search_query" field is set in the POST request
            if (isset($_POST["search_query"])) {
                $entityManager= $doctrine->getManager();
                $search_query = $_POST["search_query"];
                $selections = $entityManager->getRepository(Selection::class)->findByDescription($search_query);
            }
        }
        return $this->render('selection/index.html.twig', ['selections' => $selections]);
    }
    
    #[Route('/list', name: 'selection_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function listAction()
    {
        return $this->render('selection/selection.html.twig');
    }
    
    #[Route('/{id}', name: 'selection_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showAction(Selection $selection): Response
    {
        if (!$selection) {
            throw $this->createNotFoundException('The Selection does not exist');
        }
        
        return $this->render('selection/list.html.twig', ['selection' => $selection]);
    }
    
    #[Route('/new', name: 'selection_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $selection = new Selection();
        $form = $this->createForm(SelectionType::class, $selection);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($selection);
            $entityManager->flush();
            
            // Make sure message will be displayed after redirect
            $this->addFlash('message', 'bien ajouté');
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
            return $this->redirectToRoute('selection_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('selection/new.html.twig', [
            'selection' => $selection,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'selection_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Selection $selection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SelectionType::class, $selection);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('selection_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('selection/edit.html.twig', [
            'selection' => $selection,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'selection_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Selection $selection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$selection->getId(), $request->request->get('_token'))) {
            $entityManager->remove($selection);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('selection_list', [], Response::HTTP_SEE_OTHER);
    }
}
