<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Employe::class);
        $employes = $repository->findAll();  //select * form employe 

        return $this->render('employe/index.html.twig', [
            "employes" => $employes
        ]);
    }


    #[Route("/employe/add", name: "add_employe")]
    public function add(ManagerRegistry $doctrine, Request $request)
    {

        $employe = new Employe();
        $from = $this->createForm(EmployeType::class, $employe);
        $from->handleRequest($request);


        if ($from->isSubmitted() && $from->isValid()) {
            $manger = $doctrine->getManager();
            $manger->persist($employe);
            $manger->flush();
            return $this->redirectToRoute("app_employe");
        }


        return $this->render('employe/add.html.twig', [
            "formulaire" => $from->createView()
        ]);
    }
}
