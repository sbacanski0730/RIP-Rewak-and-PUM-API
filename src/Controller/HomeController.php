<?php
namespace App\Controller;

use App\Scraper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }

    #[Route('/scrap/{specjalnosc}', name: 'scrap')]

    public function fetch($specjalnosc): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc='.$specjalnosc;
        $pattern = '/<option value="(?<value>.*?)"/m';
        $pattern2 = '/\((?<tydzien>.\d.*?)\)/m';
        $weeks = $this->scraper->initscrap($http, $pattern, $pattern2);
        $pattern = '/<td class="nazwaDnia" colspan="4" style="font-size:13px">(?<dzien>.*?)<\/td>|<td class="godzina">(?<hours>.*?)<\/td><td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m';
        $array = $this->scraper->scrap($http, $pattern, $weeks);
        $newarray['dzien'] = $array['dzien'];
        $newarray['hours'] = $array['hours'];
        $newarray['przedmiot'] = $array['przedmiot'];
        $newarray['wykladowca'] = $array['wykladowca'];
        $newarray['sala'] = $array['sala'];


        file_put_contents('test.json', json_encode($newarray));


        return new Response('Json file generated sucesfully for speciality '.$specjalnosc);


    }




}
