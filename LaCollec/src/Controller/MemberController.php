<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Form\MemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/member')]
class MemberController extends AbstractController
{
    #[Route('/', name: 'member_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showAction(Member $member): Response
    {
        if (!$member) {
            throw $this->createNotFoundException('The Videotheque does not exist');
        }
        return $this->render('member/list.html.twig');
    }
    
    #[Route('/{id}/edit', name: 'member_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Member $member, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('member_show', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('member/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }
}

    