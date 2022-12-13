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


    #[Route('/scrap/workers/', name: 'scrap/workers')]

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

    #[Route('/scrap/workers/{wydzialid}', name: 'scrap/workers/wydzial')]

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


        file_put_contents('json_preview_sorted_workers.json', json_encode($sorted));



        return new Response('Json file generated sucesfully for workers from wydzial: '.$wydzialid);


    }


    #[Route('/scrap/workers/{wydzial}/{workerid}', name: 'scrap/worker')]

    public function fetch2($wydzial, $workerid): Response
    {

        $http = 'http://www.plan.pwsz.legnica.edu.pl/checkNauczycielAll.php?pracownik='.$workerid."&wydzial=".$wydzial;
        $pattern = '/<option value="(?<value>.*?)"/m';
        $pattern2 = '/\((?<tydzien>.\d.*?)\)/m';
        $weeks = $this->scraper->initscrap($http, $pattern, $pattern2);
        $pattern = '/t[^r]><th>(?<dzien>.*?)<|<th class="x">(?<godzinaStart>.*?)-(?<godzinaKoniec>.*?)<|<div class="blok">(?<wydzial>.*?)\X	{1,}	<div class="liniaPodzialowa">(?<sala>.*?)<\/div>\X	{1,}	(?<przedmiot>.*?)</m';
        $worker = $this->scraper->workerscrap($pattern, $weeks);

        file_put_contents('json_preview_worker.json', json_encode($worker));




        return new Response('Json file generated sucesfully for worker with id: '.$workerid.' z wydzialu o id: '.$wydzial);


    }



}
