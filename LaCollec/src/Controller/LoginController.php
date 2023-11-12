<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    
    #[Route('/logout', name: 'app_logout', methods: ['GET', 'POST'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        dump("logout");
        // throw new \Exception('Don\'t forget to activate logout in security.yaml');
        return new Response();
    }
    
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Make sure message will be displayed after redirect
            $this->addFlash('message', 'bien ajouté');
            // $this->addFlash() is equivalent to $request->getSession()->getFlashBag()->add()
            return $this->redirectToRoute('selection_home', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('login/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}

