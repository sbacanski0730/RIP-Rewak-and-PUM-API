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

    #[Route('/scrap', name: 'scrap')]

    public function fetch(): Response
    {
        $array = $this->scraper->scrap('http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc=s4PAM', '/<td class="nazwaDnia" colspan="4" style="font-size:13px">(?<dzien>.*?)<\/td>|<td class="godzina">(?<hours>.*?)<\/td><td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m');

        for ($i = 0; $i <1; $i++) {
        $removed = array_shift($array);
        }

        file_put_contents('test.json', json_encode($array));


        return new Response('Json file generated sucesfully');


    }




}
