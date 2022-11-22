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
        $przedmiot = $this->scraper->scrap('http://www.plan.pwsz.legnica.edu.pl/checkSpecjalnosc.php?specjalnosc=s4PAM');



        return new Response('Scraped subjects');


    }




}
