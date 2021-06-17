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
     * @Route("/", name="index")
     */
    public function index(): Response
    {

        $bulletin = new Bulletin;
        $bulletin->setTitle('Bleu');
        $bulletin->setCategory('General');
        $bulletin->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        ');

        return $this->render('index/index.html.twig', [
            'bulletin' => $bulletin,
        ]);
    }

    //! Pourquoi ça fonctionne alors que la page response n'existe pas ?
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
        // return new Response($option);
    }

    /**
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function indexCheatsheet(): Response
    {
        return $this->render('index/cheatsheet.html.twig');
    }
}
