<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminFormType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompteController extends AbstractController
{
    /**
     * @Route("/compte", name="compte")
     */
    public function index(): Response
    {
        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
        ]);
    }
    
    /**
     * @Route("/compte/{id}", name="compte_modifier")
     */


    public function modifier(Admin $user ,Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        
        
        $form = $this->createForm(AdminFormType::class, $user);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('compte');
        }
        

        return $this->render('compte/modifier.html.twig', [
            'users' => $user,
            'form_admin' => $form->createView()
        ]);
    }
}
