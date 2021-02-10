<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, ProduitsRepository $produitsRepository): Response
    {   

        $panier = $session->get('panier', []);

        $panierPleins = [];

        foreach ($panier as $id => $quantiter) {
            $panierPleins[] = [
                'produits' => $produitsRepository->find($id),
                'quantite' => $quantiter
            ];
        }

        $total = 0;

        foreach ($panierPleins as $i) {
            $totalPanier = $i['produits']->getPrix() * $i['quantite'];
            $total += $totalPanier;
        }

        return $this->render('panier/index.html.twig', [
            'paniers' => $panierPleins,
            'total' => $total
        ]);
    }


     /**
     * @Route("/panier/ajouter/{id}", name="panier_ajout")
     */

    public function ajouter($id, SessionInterface $session){
        
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
            $panier[$id] = 1;
        }

        $session->set('panier',$panier);


       return $this->redirectToRoute('panier');

    }

     /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */

    public function supprimer($id, SessionInterface $session){
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier',$panier);

        return $this->redirectToRoute('panier');

    }

    /**
     * @Route("/panier/moins/{id}", name="panier_moins")
     */

    public function moins($id, SessionInterface $session){
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]--;
        }
        else{
            $panier[$id] -1;
        }

        $session->set('panier',$panier);

        return $this->redirectToRoute('panier');

    }
}
