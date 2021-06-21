<?php

namespace App\Controller;

use App\Entity\Bulletin;
use App\Form\BulletinType;
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
        //* getRepository(Bulletin::class) permet de récupérer la classe bulletinRepository

        // Nous allons utiliser la méthode prédéfinie findAll() de notre Repository
        $bulletins = $bulletinRepository->findAll(); //* Retourne un tableau d'objet

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
        $bulletin = $bulletinRepository->find($bulletinId); //* Retourne un objet

        // Si $bulletin = null, on est redirigé vers l'index
        if (!$bulletin) {
            // La fonction redirect() permet de recharger une nouvelle fonction de notre index
            // La fonction generateUrl() passe l'url de la route dont le name correspond à la chaîne de caractères passées en argument
            return $this->redirect($this->generateUrl('index'));
        }

        $bulletins = [$bulletin]; //* Il faut retourner un tableau d'objet, hors $bulletin est un objet

        //Je renvoie mon tableau $bulletins à une entrée à ma page twig index.html.twig
        return $this->render('index/index.html.twig', [
            'bulletins' => $bulletins,
        ]);
    }


    /**
     * @Route("/indexCategory/{categoryName}", name="index_category")
     */
    public function indexCategory(Request $request, $categoryName)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //indexCategory est une fonction qui utilise un segment dynamique d'URL pour récupérer une dénomination de category et effectue une recherche dans le Repository de Bulletin
        $bulletins = $bulletinRepository->findByCategory($categoryName);
        //Selon la valeur indiquée par le segment optionnel d'URL $categoryName, seuls les Bulletin dont la catégorie correspond seront affichés sur la page indexCategory
        return $this->render("index/index.html.twig", ["bulletins" => $bulletins]);
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
        $bulletin = $bulletinRepository->find($bulletinId); //* find() équivaut à findOneById()
        // Si aucun bulletin correspondant à l'ID indiqué n'est trouvé, nous revenons vers l'index
        if (!$bulletin) {
            return $this->redirect($this->generateUrl('index'));
        }
        // Si nous trouvons un bulletin, nous procédons à sa demande de suppression via l'Entity Manager
        $entityManager->remove($bulletin);
        $entityManager->flush();
        //Nous retournons à l'index à présent que notre fonction a rempli son rôle
        return $this->redirect($this->generateUrl('index'));
    }


    /**
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function indexCheatsheet(): Response
    {
        return $this->render('index/cheatsheet.html.twig');
    }


    /**
     * @Route("/create/bulletin", name="bulletin_create")
     */
    public function createBulletin(Request $request)
    {
        // Nous récupérons l'Entity Manager afin de préparer l'envoi du Bulletin à créer
        $entityManager = $this->getDoctrine()->getManager();

        // Nous créons un Bulletin vide prêt à l'emploi et nous le lions à notre BulletinType
        $bulletin = new Bulletin;
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);

        // Nous récupérons les informations de la requête utilisateur pour notre formulaire
        $bulletinForm->handleRequest($request);

        // Une fois le Bulletin validé, nous procédons à sa mise en ligne dans la BDD
        if ($request->isMethod('post') && $bulletinForm->isValid()) {
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }

        // Nous envoyons notre Bulletin dans le fichier Twig approprié
        return $this->render('index/dataform.html.twig', [
            'dataForm' => $bulletinForm->createView(),
            'formName' => 'Création de bulletin',
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
}
