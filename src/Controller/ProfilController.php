<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    private $userRepo;

    public function __construct(UtilisateurRepository $userRepo){
        $this->userRepo = $userRepo;
    }
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
{
    $user = $this->getUser();

    if (!$user) {
        
        return $this->redirectToRoute('app_login');
    }

    $identifiant = $user->getUserIdentifier(); 
    $info = $this->userRepo->findOneBy(["email" => $identifiant]);

    return $this->render('profil/index.html.twig', [
        'informations' => $info
    ]);
}

}