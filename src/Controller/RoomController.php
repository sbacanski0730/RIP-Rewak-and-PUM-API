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

<<<<<<< HEAD
    #[Route('/scrap/buildings', name: 'scrap/buildings')]
=======
    #[Route('/hello', name: 'hello')]
    function hello() {
        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_sala.php&id=10';
        $pattern = '/#">(?<budynek>.*?)</m';

        $array_buildings = $this->scraper->buildingscrap($http, $pattern);


        return new JsonResponse($array_buildings);
    }

    #[Route('/scrap/rooms', name: 'scrap/rooms')]
>>>>>>> parent of 95e4130 (Revert "route and json names normalization")

    public function fetch(): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_sala.php&id=10';
        $pattern = '/#">(?<budynek>.*?)</m';

        $array_buildings = $this->scraper->buildingscrap($http, $pattern);


        file_put_contents('rooms.json', json_encode($array_buildings));


        return new Response('Json file generated sucesfully for buildings');


    }

    #[Route('/scrap/rooms/{buildingname}', name: 'scrap/rooms/building')]

    public function fetch2($buildingname): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_sala.php&id=10';
        $pattern = '/<div><a href="checkBudynek\.php\?(?<chwytak>.*?)<\/li>/m';

        $array_rooms = $this->scraper->roominbuildscrap($http, $pattern);
        $sorted=[];
        foreach ($array_rooms as $rooms)
        {
            if ($rooms->budynek == $buildingname) $sorted[]=$rooms;
        }

        file_put_contents('rooms-'.$buildingname.'.json', json_encode($sorted));


        return new Response('Json file generated sucesfully for building: '.$buildingname);


    }

}
