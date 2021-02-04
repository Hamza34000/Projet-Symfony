<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
