<?php

namespace App\Controller;

use App\Entity\Bulletin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name = "index")
     */
    public function index(): Response
    {

        // Nous allons nous connecter à notre base de données via l'Entity Manager
        // Nous récupérons ensuite le Repository de Bulletin lequel nous permet d'effectuer des recherches
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);

        // Nous allons utiliser la méthode prédéfinie findAll() de notre Repository
        $bulletins = $bulletinRepository->findAll();

        return $this->render('index/index.html.twig', [
            'bulletins' => $bulletins,
        ]);
    }

    /**
     * @Route("/bulletin/{bulletinId}", name="bulletin_display")
     */
    public function bulletinDisplay(Request $request, $bulletinId = false)
    {
        //La fonction bulletinDisplay a pour but de n'afficher qu'un seul bulletin, sélectionné par son ID
        //Nous récupérons d'abord notre Entity Manager et le Repository qui nous intéresse, celui de Bulletin
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);

        // La fonction find() du Repository permet de récupérer un seul objet selon son ID
        $bulletin = $bulletinRepository->find($bulletinId);

        // Si $bulletin = null, on est redirigé vers l'index
        if (!$bulletin) {
            // La fonction redirect() permet de recharger une nouvelle fonction de notre index
            // La fonction generateUrl() passe l'url de la route dont le name correspond à la chaîne de caractères passées en argument
            return $this->redirect($this->generateUrl('index'));
        }

        $bulletins = [$bulletin];

        //Je renvoie mon tableau $bulletins à une entrée à ma page twig index.html.twig
        return $this->render('index/index.html.twig', [
            'bulletins' => $bulletins,
        ]);
    }


    /**
     * @Route("/response/{option}", name="index_response")
     */
    public function indexResponse(Request $request, $option = "vert"): Response
    {
        //Ctrl + clic droit -> Import All Classes

        switch ($option) {
            case 'rouge':
                $figure = 'red';
                break;
            case 'vert':
                $figure = 'green';
                break;
            case 'bleu':
                $figure = 'blue';
                break;
            default:
                $figure = "black";
        }

        return new Response("<div style='width:200px; height:300px; background-color : " . $figure . ";'></div>");
    }

    /**
     * @Route("/delete/bulletin/{bulletinId}", name = "bulletin_delete")
     */
    public function bulletinDelete(Request $request, $bulletinId)
    {
        // Nous récupérons l'Entity Manager et le Repository qui nous intéresse (Bulletin)
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);

        // Nous récupérons le Bulletin concerné via l'ID présente dans bulletinId
        $bulletin = $bulletinRepository->find($bulletinId);
    }




    /**
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function indexCheatsheet(): Response
    {
        return $this->render('index/cheatsheet.html.twig');
    }
}
