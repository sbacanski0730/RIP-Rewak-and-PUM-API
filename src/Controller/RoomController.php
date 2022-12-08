<?php
namespace App\Controller;



use App\Scraper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends AbstractController
{
    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }

    #[Route('/scrap/buildings', name: 'scrap/buildings')]

    public function fetch(): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_sala.php&id=10';
        $pattern = '/#">(?<budynek>.*?)</m';

        $array_buildings = $this->scraper->buildingscrap($http, $pattern);


        file_put_contents('json_preview_Buildings.json', json_encode($array_buildings));


        return new Response('Json file generated sucesfully for buildings');


    }

}
