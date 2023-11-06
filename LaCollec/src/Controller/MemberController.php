<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;

#[Route('/member')]
class MemberController extends AbstractController
{
    #[Route('/', name: 'member_show', methods: ['GET'])]
    public function showAction(Member $member): Response
    {
        if (!$member) {
            throw $this->createNotFoundException('The Videotheque does not exist');
        }
        return $this->render('member/list.html.twig');
    }
}
