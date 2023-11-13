<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Videotheque;
use App\Form\VideothequeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/videotheque')]
class VideothequeController extends AbstractController
{   
    #[Route('/list', name: 'videotheque_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function listAction(ManagerRegistry $doctrine)
    {
        return $this->render('videotheque/videotheque.html.twig');
    }
    
    #[Route('/{id}', name: 'videotheque_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showAction(Videotheque $videotheque): Response
    {
        if (!$videotheque) {
            throw $this->createNotFoundException('The Videotheque does not exist');
        }
        
        return $this->render('videotheque/list.html.twig', ['videotheque' => $videotheque]);
    }
    
    #[Route('/new', name: 'videotheque_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $videotheque = new Videotheque();
        $form = $this->createForm(VideothequeType::class, $videotheque);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($videotheque);
            $entityManager->flush();
            
            // Make sure message will be displayed after redirect
            $this->addFlash('message', 'bien ajouté');
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
            
            return $this->redirectToRoute('videotheque_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('videotheque/new.html.twig', [
            'paste' => $videotheque,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'videotheque_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Videotheque $videotheque, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VideothequeType::class, $videotheque);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('videotheque_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('videotheque/edit.html.twig', [
            'videotheque' => $videotheque,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'videotheque_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Videotheque $videotheque, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$videotheque->getId(), $request->request->get('_token'))) {
            foreach($videotheque->getContenu() as $video) {
                $entityManager->remove($video);
                $entityManager->flush();
            }
            $entityManager->remove($videotheque);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('videotheque_list', [], Response::HTTP_SEE_OTHER);
    }
}
