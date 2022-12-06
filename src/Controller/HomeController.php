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



        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_nauczyciel.php';
        $pattern = '/show_kierunek.php&amp;id=(?<numer_wydzialu>.*?)">(?<nazwa_wydzialu>.*?)</m';
        $wydzial = $this->scraper->wydzialscrap($http,  $pattern);
        $pattern = '/checkNauczycielAll\.php\?pracownik=(?<numer_pracownik>.*?)&amp;wydzial=(?<wydzial>.*?)" target="_blank">((?<pracownik>.*?))</m';
        $name = $this->scraper->pracscrap($pattern);
        $http = 'http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc='.$specjalnosc;
        $pattern = '/<option value="(?<value>.*?)"/m';
        $pattern2 = '/\((?<tydzien>.\d.*?)\)/m';
        $weeks = $this->scraper->initscrap($http, $pattern, $pattern2);
        $pattern = '/<td class="nazwaDnia" colspan="4" style="font-size:13px">(?<dzien>.*?)<\/td>|<td class="godzina">(?<hours>.*?)<\/td><td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m';
        $array = $this->scraper->scrap($pattern, $weeks);


        file_put_contents('json_preview_wydzial.json', json_encode($wydzial));
        file_put_contents('json_preview_name.json', json_encode($name));
        file_put_contents('json_preview.json', json_encode($array));


        return new Response('Json file generated sucesfully for speciality '.$specjalnosc);


    }




}
