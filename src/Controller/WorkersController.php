<?php
namespace App\Controller;

use App\Scraper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class WorkersController extends AbstractController
{

    private Scraper $scraper;

    public function __construct(Scraper $scraper)
    {
        $this->scraper = $scraper;
    }


    #[Route('/scrap/workers-group/', name: 'scrap/workers-group')]

    public function fetch(): Response
    {



        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_nauczyciel.php';
        $pattern = '/show_kierunek.php&amp;id=(?<numer_wydzialu>.*?)">(?<nazwa_wydzialu>.*?)</m';
        $wydzial = $this->scraper->wydzialscrap($http,  $pattern);
        $pattern = '/checkNauczycielAll\.php\?pracownik=(?<numer_pracownik>.*?)&amp;wydzial=(?<wydzial>.*?)" target="_blank">((?<pracownik>.*?))</m';
        $name = $this->scraper->pracscrap($pattern);






        return new JsonResponse($wydzial);


    }

    #[Route('/scrap/workers-group/{wydzialid}', name: 'scrap//workers-group/wydzial')]

    public function fetch3($wydzialid): Response
    {



        $http = 'http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_nauczyciel.php';
        $pattern = '/show_kierunek.php&amp;id=(?<numer_wydzialu>.*?)">(?<nazwa_wydzialu>.*?)</m';
        $wydzial = $this->scraper->wydzialscrap($http,  $pattern);
        $pattern = '/checkNauczycielAll\.php\?pracownik=(?<numer_pracownik>.*?)&amp;wydzial=(?<wydzial>.*?)" target="_blank">((?<pracownik>.*?))</m';
        $array_workers = $this->scraper->pracscrap($pattern);
        $sorted=[];
        foreach ($array_workers as $worker)
        {
            if ($worker->wydzial == $wydzialid) $sorted[]=$worker;
        }






        return new JsonResponse($sorted);


    }


    #[Route('/scrap/workers-group/{wydzialid}/{workerid}', name: 'scrap/worker')]

    public function fetch2($wydzialid, $workerid): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/checkNauczycielAll.php?pracownik='.$workerid."&wydzial=".$wydzialid;
        $pattern = '/<option value="(?<value>.*?)"/m';
        $pattern2 = '/\((?<tydzien>.\d.*?)\)/m';
        $weeks = $this->scraper->initscrap($http, $pattern, $pattern2);
        $pattern = '/t[^r]><th>(?<dzien>.*?)<|<th class="x">(?<godzinaStart>.*?)-(?<godzinaKoniec>.*?)<|<div class="blok">(?<wydzial>.*?)\X	{1,}	<div class="liniaPodzialowa">(?<sala>.*?)<\/div>\X	{1,}	(?<przedmiot>.*?)</m';
        $worker = $this->scraper->workerscrap($pattern, $weeks);






        return new JsonResponse($worker);


    }



}
