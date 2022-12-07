<?php
namespace App\Controller;



use App\Scraper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SpecialityController extends AbstractController
{
    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }

    #[Route('/scrap/speciality/{specjalnosc}', name: 'scrap/speciality')]

    public function fetch($specjalnosc): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc='.$specjalnosc;
        $pattern = '/<option value="(?<value>.*?)"/m';
        $pattern2 = '/\((?<tydzien>.\d.*?)\)/m';
        $weeks = $this->scraper->initscrap($http, $pattern, $pattern2);
        $pattern = '/<td class="nazwaDnia" colspan="4" style="font-size:13px">(?<dzien>.*?)<\/td>|<td class="godzina">(?<hours>.*?)<\/td><td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m';
        $array = $this->scraper->scrap($pattern, $weeks);

        file_put_contents('json_preview.json', json_encode($array));


        return new Response('Json file generated sucesfully for speciality '.$specjalnosc);


    }

}