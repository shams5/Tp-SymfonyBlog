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


        $bulletinInfos = [
            ["title" => "Bleu", "category" => "info", "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."],
            ["title" => "Rouge", "category" => "danger", "content" => "Dans une ville de province, le jour où sa femme accouche, un pharmacien de cinquante ans perd un peu la tête et met du cyanure dans son célèbre sirop Soleil. S'étant aperçu de la méprise, il passe la nuit à retrouver les cinq clients à qu'il a vendu les flacons du sirop. Le premier est un notable, le second, un assureur, le troisième, un nain travaillant dans un cirque et le quatrième, un ancien collaborateur responsable de la mort d'un résistant..."],
            ["title" => "Orange", "category" => "warning", "content" => "Ces petits poils à peine visibles à l’œil nu, sont remplis d’un cocktail de substances responsables de la rougeur, la brulure et de la démangeaison associé à la piqure de l’ortie. Parmi ces composés, on peut en citer deux majeurs : l’acide formique et l’histamine.

            Les poils des orties sont terminés par une sorte de petit pic en silice qui se casse au moindre contact. Il laisse alors l’extrémité du poil urticant en forme de biseau traverser facilement. A la base du poil, comme tu peux le voir si dessous, il y a une glande qui produit les composés urticants. Ces composés sont stockés à l’intérieur du poil dans une vacuole et sont libérés une fois le poil cassé.
            
            Comme tu viens de le comprendre le poil une fois cassé et se trouvant dans la peau, le cocktail urticant est directement libéré dans notre corps !"],
            ["title" => "Noir", "category" => "dark", "content" => "blablablablablablablablablablablablablablablablablablablablablablablablablablablablabla"],
            ["title" => "Vert", "category" => "success", "content" => "Forrest Gump est un personnage fictif, un simple d'esprit tout d'abord apparu dans le roman homonyme de Winston Groom en 1986 puis dans le film homonyme réalisé par Robert Zemeckis en 1994. Forrest est interprété par Michael Conner Humphreys enfant et par Tom Hanks en tant qu'adulte. Le personnage du livre est différent de celui du film. En 2008, il a été nommé le 20e plus grand personnage de cinéma de tous les temps par le magazine Empire1."],
        ];

        $bulletins = [];

        foreach ($bulletinInfos as $bulletinInfo) {
            $bulletin = new Bulletin;
            $bulletin->setTitle($bulletinInfo["title"]);
            $bulletin->setCategory($bulletinInfo["category"]);
            $bulletin->setContent($bulletinInfo["content"]);
            $bulletins[] = $bulletin;
            //* ou bien : array_push($bulletins, $bulletin); 
        }

        return $this->render('index/index.html.twig', ["bulletins" => $bulletins]);
    }

    /**
     * @Route("/bulletin/{bulletinId}", name="bulletin_display")
     */
    public function bulletinDisplay(Request $request, $bulletinId = null): Response
    {

        $bulletinInfos = [
            ["title" => "Bleu", "category" => "info", "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."],
            ["title" => "Rouge", "category" => "danger", "content" => "Dans une ville de province, le jour où sa femme accouche, un pharmacien de cinquante ans perd un peu la tête et met du cyanure dans son célèbre sirop Soleil. S'étant aperçu de la méprise, il passe la nuit à retrouver les cinq clients à qu'il a vendu les flacons du sirop. Le premier est un notable, le second, un assureur, le troisième, un nain travaillant dans un cirque et le quatrième, un ancien collaborateur responsable de la mort d'un résistant..."],
            ["title" => "Orange", "category" => "warning", "content" => "Ces petits poils à peine visibles à l’œil nu, sont remplis d’un cocktail de substances responsables de la rougeur, la brulure et de la démangeaison associé à la piqure de l’ortie. Parmi ces composés, on peut en citer deux majeurs : l’acide formique et l’histamine.

            Les poils des orties sont terminés par une sorte de petit pic en silice qui se casse au moindre contact. Il laisse alors l’extrémité du poil urticant en forme de biseau traverser facilement. A la base du poil, comme tu peux le voir si dessous, il y a une glande qui produit les composés urticants. Ces composés sont stockés à l’intérieur du poil dans une vacuole et sont libérés une fois le poil cassé.
            
            Comme tu viens de le comprendre le poil une fois cassé et se trouvant dans la peau, le cocktail urticant est directement libéré dans notre corps !"],
            ["title" => "Noir", "category" => "dark", "content" => "blablablablablablablablablablablablablablablablablablablablablablablablablablablablabla"],
            ["title" => "Vert", "category" => "success", "content" => "Forrest Gump est un personnage fictif, un simple d'esprit tout d'abord apparu dans le roman homonyme de Winston Groom en 1986 puis dans le film homonyme réalisé par Robert Zemeckis en 1994. Forrest est interprété par Michael Conner Humphreys enfant et par Tom Hanks en tant qu'adulte. Le personnage du livre est différent de celui du film. En 2008, il a été nommé le 20e plus grand personnage de cinéma de tous les temps par le magazine Empire1."],
        ];

        if (is_numeric($bulletinId) && $bulletinId > 0 && $bulletinId <= count($bulletinInfos)) {
            $selectedInfo = $bulletinInfos[($bulletinId - 1)];
            $bulletin = new Bulletin;
            $bulletin->setTitle($selectedInfo["title"]);
            $bulletin->setCategory($selectedInfo["category"]);
            $bulletin->setContent($selectedInfo["content"]);

            $bulletins = [$bulletin];
        } else {
            $bulletins = [];
        }

        return $this->render('index/index.html.twig', ["bulletins" => $bulletins]);
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
     * @Route("/cheatsheet", name="index_cheatsheet")
     */
    public function indexCheatsheet(): Response
    {
        return $this->render('index/cheatsheet.html.twig');
    }
}
