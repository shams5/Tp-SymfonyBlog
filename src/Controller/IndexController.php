<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Entity\Bulletin;
use App\Form\BulletinType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name = "index")
     */
    public function index(Request $request): Response
    {

        // Nous allons nous connecter à notre base de données via l'Entity Manager
        // Nous récupérons ensuite le Repository de Bulletin lequel nous permet d'effectuer des recherches
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        //* getRepository(Bulletin::class) permet de récupérer la classe bulletinRepository

        // Nous allons utiliser la méthode prédéfinie findAll() de notre Repository
        $bulletins = $bulletinRepository->findAll(); //* Retourne un tableau d'objet

        //* Ici, nous préparons un formulaire de création de bulletin sur notre page d'accueil :
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

        return $this->render('index/index.html.twig', [
            'dataForm' => $bulletinForm->createView(),
            'bulletins' => array_reverse($bulletins),
        ]);
    }


    /**
     * @Route("/tag/display/{tagName}", name="tag_display")
     */
    public function tagDisplay(Request $request, $tagName)
    {
        // Cette fonction affiche le nom d'un tag ainsi que la liste des bulletins qui lui sont liés
        // Pour cela nous devons dialoguer avec la BDD : nous récupérons l'Entity Manager et le Repository pertinent
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        $tag = $tagRepository->findOneByName($tagName);
        // Si le tag n'existe pas, nous revenons vers la liste des tags
        if (!$tag) {
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('index/tagdiplay.html.twig', [
            "tag" => $tag
        ]);
    }

    /**
     * @Route("/tag", name="tag_list")
     */
    public function tagList()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();

        return $this->render('index/tag.html.twig', [
            "tags" => $tags
        ]);
    }

    /**
     * @Route("/create/tag", name="tag_create")
     */
    public function tagCreate(Request $request)
    {
        // Cette fonction a pour but d'afficher le formulaire de création de Tags
        // Pour communiquer avec notre BDD, nous avons besoin de l'Entity Manager
        $entityManager = $this->getDoctrine()->getManager();

        // Nous créons un nouveau Tag et nous le lions au formulaire à créer
        $tag = new Tag;
        $tagForm = $this->createForm(TagType::class, $tag);

        // Nous transmettons la requête au formulaire avant de vérifier sa validité
        // Nous renvoyons ensuite l'utilisateur vers la liste de Tags afin qu'il puisse constater l'ajout
        $tagForm->handleRequest($request);
        if ($request->isMethod('post') && $tagForm->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('tag_list'));
        }

        // Nous transmettons notre nouveau formulaire à dataform.html.twig
        return $this->render('index/dataform.html.twig', [
            'dataForm' => $tagForm->createView(),
            'formName' => 'Création de tag',
        ]);
    }


    /**
     * @Route("/create/tags",name="tags_create")
     */
    public function createMultipleTags(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tagsForm = $this->createFormBuilder()
            ->add('name1', TextType::class, [
                'label' => 'Nom du tag',

                'required' => false

            ])
            ->add('name2', TextType::class, [
                'label' => 'Nom du tag',

                'required' => false

            ])
            ->add('name3', TextType::class, [
                'label' => 'Nom du tag',

                'required' => false

            ])
            ->add('name4', TextType::class, [
                'label' => 'Nom du tag',

                'required' => false

            ])
            ->add('name5', TextType::class, [
                'label' => 'Nom du tag',

                'required' => false

            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'style' => 'margin-top : 5px',
                    'class' => 'btn btn-success',
                ],
            ])
            ->getForm();

        //* Nous transmettons la requête client à notre formulaire, et s'il est valide, nous transmettons les Tags créés à la BDD
        $tagsForm->handleRequest($request);

        if ($request->isMethod('post') && $tagsForm->isValid()) {
            //* getData() est une fonction récupérant les valeurs de chaque champ de formulaire et les transmet sous la forme d'un tableau associatif (clef => champ, valeur => valeur)
            $datas = $tagsForm->getData();
            // Nous allons utiliser une boucle foreach pour parcourir chaque champ et créer un Tag si ce dernier a été rempli
            foreach ($datas as $data) {
                if ($data) {
                    $tag = new Tag;
                    $tag->setName((string)$data);
                    $entityManager->persist($tag);
                }
            }
            // Nous appliquons chaque demande de persist faite lors de la boucle avant une redirection vers tagList
            $entityManager->flush();
            return $this->redirect($this->generateUrl('tag_list'));
        }

        return $this->render('index/dataform.html.twig', [
            "formName" => "Création de tags multiples",
            "dataForm" => $tagsForm->createView()
        ]);
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
     * @Route("/edit/bulletin/{bulletinId}",name="bulletin_edit")
     */
    public function editBulletin(Request $request, $bulletinId)
    {
        // Cette fonction a pour but de modifier un bulletin enregistré dans notre BDD
        // Elle récupère pour ce but le bulletin dont l'ID est indiqué et l'intégre à un formulaire
        // Nous faisons donc appel à l'Entity Manager et au Repository pertinent
        $entityManager = $this->getDoctrine()->getManager();
        $bulletinRepository = $entityManager->getRepository(Bulletin::class);
        // Nous recherchons le Bulletin dont l'ID est indiqué. Le cas échéant, nous revenons vers l'index
        $bulletin = $bulletinRepository->find($bulletinId);
        if (!$bulletin) {
            return $this->redirect($this->generateUrl('index'));
        }
        // Une fois le bulletin récupéré, nous créons un nouveau formulaire BulletinType auquel il sera lié
        $bulletinForm = $this->createForm(BulletinType::class, $bulletin);
        // Nous transmettons les valeurs de $request à notre bulletin
        // Si le formulaire est rempli et validé, nous le transmettons à notre BDD
        $bulletinForm->handleRequest($request);
        if ($request->isMethod('post') && $bulletinForm->isValid()) {
            $entityManager->persist($bulletin);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        // Nous requérons un render du template dataform.html.twig
        return $this->render('index/dataform.html.twig', [
            'formName' => 'Modification de Bulletin',
            'dataForm' => $bulletinForm->createView(),
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
        return $this->render("index/index.html.twig", [
            "bulletins" => array_reverse($bulletins)
        ]);
    }

    /**
     * @Route("/delete/tag/{tagId}", name="tag_delete")
     */
    public function tagDelete(Request $request, $tagId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        $tag = $tagRepository->find($tagId);
        if (!$tag) {
            return $this->redirect($this->generateUrl('tag_list'));
        }
        $entityManager->remove($tag);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('tag_list'));
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
}
