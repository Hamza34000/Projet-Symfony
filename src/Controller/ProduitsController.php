<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\AdminFormType;
use App\Form\ProduitsFormType;
use App\Repository\AdminRepository;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategorieRepository $categorieRepository, ProduitsRepository $produitsRepository): Response
    {
        $categories = $categorieRepository->findAll();
        $produits = $produitsRepository->findAll();
        return $this->render('produits/produits.html.twig', [
            'categories' => $categories,
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/produits", name="produits")
     */

    public function voir(ProduitsRepository $produitsRepository, Request $request): Response
    {
        $idCategorie = $request->query->get('categorie');
        if($idCategorie > 5)
        {
            $produits = $produitsRepository->findAll();
        }
        else{
        $produits = $produitsRepository->getListCategorie($idCategorie);
        }
    

        return $this->render('produits/show.html.twig', [
            'produits' => $produits,

        ]);
    }

    /**
     * @Route("/admin_produits", name="admin_produits")
     */

     public function admin(ProduitsRepository $produitsRepository, AdminRepository $adminRepository){

        $produits = $produitsRepository->findAll();
        $users = $adminRepository->findAll();
        return $this->render('produits/admin.html.twig', [
            'produits' => $produits,
            'users' => $users

        ]);
     }

     /**
     * @Route("/newproduits", name="admin_newproduits")
     */
    public function newProduits(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsFormType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if(empty($produit->getPhoto())){
            $produit->setPhoto('images/new.jpg');
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produits');
        }
        return $this->render('produits/newproduits.html.twig', [
            'produits' => $produit,
            'form_produits' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin_produits/{id}/deleteproduits", name="admin_deleteproduits")
     */
    public function deleteProduits( Produits $produits , EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($produits);
        $entityManager->flush();

        return $this->redirectToRoute('admin_produits',['id' => $produits->getId()]);
    }

    /**
     * @Route("/admin_produits/{id}", name="admin_editproduits")
     */
    public function editProduits(Produits $produits, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $form = $this->createForm(ProduitsFormType::class, $produits);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($produits);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produits');
        }
        return $this->render('produits/editproduits.html.twig', [
            'produits' => $produits,
            'form_produits' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin_produits/ajouter/{id}", name="stock_ajout")
     */

    public function ajouter( Produits $produits, EntityManagerInterface $entityManager ){
        
        
        $stock = ($produits->getStock()) +1;

        
            $produits->setStock($stock);
            $entityManager->persist($produits);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produits');

    }

    /**
     * @Route("/admin_produits/supprimer/{id}", name="stock_supprimer")
     */

     public function supprimer(Produits $produits, EntityManagerInterface $entityManager)
     {
         $stock = ($produits->getStock())-1;

         $produits->setStock($stock);
         $entityManager->persist($produits);
         $entityManager->flush();

         return $this->redirectToRoute('admin_produits');
     }

     /**
     * @Route("/produits/{id}", name="produits_description")
     */

     public function ficheProduit(Produits $produits)
     {
        return $this->render('produits/fiche.html.twig', [
            'produits' => $produits,

        ]);
     }

    



}
