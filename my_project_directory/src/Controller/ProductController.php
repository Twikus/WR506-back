<?php

namespace App\Controller;

use App\Services\SlugifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product_list')]
    public function listProducts(): Response
    {
        return $this->render('product/list.html.twig', [
            'title' => 'Liste des produits',
        ]);
    }
    
    #[Route('/product/{id}', name: 'product_view')]
    public function viewProduct(Request $request): Response
    {
        $productId = $request->get('id');

        return $this->render('product/view.html.twig', [
            'title' => 'Affichage du produit ' . $productId,
        ]);
    }

    #[Route('/product/slug/{phrase}', name: 'slugify_phrase')]
    public function slugifyPhrase(SlugifyService $slugifyService, string $phrase): Response
    {
        $slug = $slugifyService->slugify($phrase);

        return new Response('Slug : ' . $slug);
    }
}
