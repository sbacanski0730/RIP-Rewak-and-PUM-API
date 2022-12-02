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
        $weeks = $this->scraper->scrap('http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc=s4PAM', '/<option value="(?<value>.*?)".*? .*? .*? .(?<tydzien>.*?).?+</m');
        $newweeks['value'] = $weeks['value'];
        $newweeks['tydzien'] = $weeks['tydzien'];
        $array = $this->scraper->scrap('http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc=s4PAM', '/<td class="nazwaDnia" colspan="4" style="font-size:13px">(?<dzien>.*?)<\/td>|<td class="godzina">(?<hours>.*?)<\/td><td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m', $newweeks);
        $newarray['dzien'] = $array['dzien'];
        $newarray['hours'] = $array['hours'];
        $newarray['przedmiot'] = $array['przedmiot'];
        $newarray['wykladowca'] = $array['wykladowca'];
        $newarray['sala'] = $array['sala'];


        file_put_contents('test.json', json_encode($newarray));


        return new Response('Json file generated sucesfully');


    }




}
