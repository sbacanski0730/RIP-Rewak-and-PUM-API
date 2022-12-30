<?php
namespace App\Controller;



use App\Scraper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomController extends AbstractController
{
    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }




    #[Route('/scrap/rooms', name: 'scrap/rooms')]

    public function fetch(): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_sala.php&id=10';
        $pattern = '/#">(?<budynek>.*?)</m';

        $array_buildings = $this->scraper->buildingscrap($http, $pattern);





        return new JsonResponse($array_buildings);


    }

    #[Route('/scrap/rooms/{buildingname}', name: 'scrap/rooms/building')]

    public function fetch2($buildingname): Response
    {
        #todo: dodać zmniejszenie 1 litery w zmiennej $buildingname
        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_sala.php&id=10';
        $pattern = '/<div><a href="checkBudynek\.php\?(?<chwytak>.*?)<\/li>/m';

        $array_rooms = $this->scraper->roominbuildscrap($http, $pattern);
        $sorted=[];
        foreach ($array_rooms as $rooms)
        {
            if ($rooms->budynek == $buildingname) $sorted[]=$rooms;
        }




        return new JsonResponse($sorted);


    }

}
