<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Video;
use App\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/video')]
class VideoController extends AbstractController
{
    #[Route('/list', name: 'video_list', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function listAction(ManagerRegistry $doctrine)
    {
        return $this->render('video/video.html.twig');
    }
    
    #[Route('/{id}', name: 'video_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showAction(Video $video): Response
    {
        if (!$video) {
            throw $this->createNotFoundException('The Video does not exist');
        }
        
        return $this->render('video/list.html.twig',['video' => $video]);
    }
    
    #[Route('/new', name: 'video_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Change content-type according to image's
            $imagefile = $video->getImageFile();
            if($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $video->setContentType($mimetype);
            }
            
            $entityManager->persist($video);
            $entityManager->flush();
            
            // Make sure message will be displayed after redirect
            $this->addFlash('message', 'bien ajouté');
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
            
            return $this->redirectToRoute('video_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('video/new.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'video_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Video $video, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('video_list', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('video/edit.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'video_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Video $video, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $entityManager->remove($video);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('video_list', [], Response::HTTP_SEE_OTHER);
    }
    
}
