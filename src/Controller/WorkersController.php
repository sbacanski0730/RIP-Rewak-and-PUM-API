<?php
namespace App\Controller;

use App\Scraper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class WorkersController extends AbstractController
{

    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }


    #[Route('/scrap/workers', name: 'scrap/workers')]

    public function fetch(): Response
    {



        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_nauczyciel.php';
        $pattern = '/show_kierunek.php&amp;id=(?<numer_wydzialu>.*?)">(?<nazwa_wydzialu>.*?)</m';
        $wydzial = $this->scraper->wydzialscrap($http,  $pattern);
        $pattern = '/checkNauczycielAll\.php\?pracownik=(?<numer_pracownik>.*?)&amp;wydzial=(?<wydzial>.*?)" target="_blank">((?<pracownik>.*?))</m';
        $name = $this->scraper->pracscrap($pattern);


        file_put_contents('json_preview_wydzial.json', json_encode($wydzial));
        file_put_contents('json_preview_name.json', json_encode($name));



        return new Response('Json file generated sucesfully for workers');


    }



}
