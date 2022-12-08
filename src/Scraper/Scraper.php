<?php

namespace App\Scraper;



use Goutte\Client;


class Pracownik {
    public $name;
    public $numer;
    public $wydzial;
}
class Wydzial {
    public $name;
    public $number;
}
class Wyklad {
    public $date;
    public $timeStart;
    public $timeEnd;
    public $subject;
    public $room;
    public $wydzial;
    public $lecturer;
    public $grupa;
}

class Budynek
{
    public $budynek;
    public $sala;
    public $connect;

}

class Scraper
{
    private $client;
    private $crawler;

    public function __construct()
    {
        $this->client = new Client();

    }

    public function wydzialscrap($source, $pattern)
    {

        $this->crawler = $this->client->request('GET', $source);
        $output = $this->crawler->filter('body');
        $html = $output->outerHtml();


        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);


        preg_match_all($pattern, $htmlSection, $matches);
        $results['nazwa'] = $matches['nazwa_wydzialu'];
        $results['numer'] = $matches['numer_wydzialu'];


        $objects = [];


        $length = count($results['nazwa']);


        for ($i = 0; $i < $length; $i++) {
            $object = new Wydzial();


            $object->name = $results['nazwa'][$i];
            $object->number = $results['numer'][$i];

            $objects[] = $object;
        }

        return $objects;
    }

    public function pracscrap($pattern)
    {


        $output = $this->crawler->filter('body');
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);


        preg_match_all($pattern, $htmlSection, $matches);
        $results['name'] = $matches['pracownik'];
        $results['numer_pracownik'] = $matches['numer_pracownik'];
        $results['wydzial_pracownik'] = $matches['wydzial'];


        $objects = [];


        $length = count($results['name']);


        for ($i = 0; $i < $length; $i++) {
            $object = new Pracownik();


            $object->name = $results['name'][$i];
            $object->numer = $results['numer_pracownik'][$i];
            $object->wydzial = $results['wydzial_pracownik'][$i];

            $objects[] = $object;
        }



        return $objects;
    }


    public function initscrap($source, $pattern, $pattern2)
    {
        $this->crawler = $this->client->request('GET', $source);

        $output = $this->crawler->filter('body');
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);
        $results['value'] = $matches['value'];
        preg_match_all($pattern2, $htmlSection, $matches);
        $results['tydzien'] = $matches['tydzien'];

        return $results;
    }

    public function scrap($pattern, $pattern2, $pattern3, $weeks)
    {

        $form = $this->crawler->filter('form')->form();
        $objects = [];

        foreach ($weeks['value'] as $week) {
            $results['dzien'] = $results['godzina_start'] = $results['godzina_koniec'] = $results['przedmiot'] = $results['wykladowca'] = $results['sala'] = $results['grupa'] = [];;
            $this->crawler = $this->client->submit($form, ['dzien' => $week]);
            $output = $this->crawler->filter('body');
            $html = $output->outerHtml();

            $start = stripos($html, '<body>');
            $end = stripos($html, '</body>', $offset = $start);
            $length = $end - $start;
            $htmlSection = substr($html, $start, $length);

            preg_match_all($pattern, $htmlSection, $matches);
            preg_match_all($pattern2, $htmlSection, $matches2);
            preg_match_all($pattern3, $htmlSection, $matches3);

            $results['grupa'] = array_merge($results['grupa'], $matches['grupa']);
            $results['dzien'] = array_merge($results['dzien'], $matches2['dzien']);
            $results['godzina_start'] = array_merge($results['godzina_start'], $matches3['godzinaStart']);
            $results['godzina_koniec'] = array_merge($results['godzina_koniec'], $matches3['godzinaKoniec']);
            $results['przedmiot'] = array_merge($results['przedmiot'], $matches3['przedmiot']);
            $results['wykladowca'] = array_merge($results['wykladowca'], $matches3['wykladowca']);
            $results['sala'] = array_merge($results['sala'], $matches3['sala']);


            $length = count($results['grupa']);

            $lenghth3 = count($results['dzien']);

            for ($g = 0; $g < $length; $g ++) {
                $h=1+$g;
                for ($d = 0; $d<$lenghth3; $d++){
                    for (;$h < ($d+1)*((1+$length)*7) ; $h += (1+$length)) {
                        if (!(($results['przedmiot'][$h] == "-") OR ($results['przedmiot'][$h] == "")) ){
                        $object = new Wyklad();
                        $object->date = $results['dzien'][$d];
                        $object->grupa = $results['grupa'][$g];
                        $object->timeStart = $results['godzina_start'][$h-1-$g];
                        $object->timeEnd = $results['godzina_koniec'][$h-1-$g];
                        $object->subject = $results['przedmiot'][$h];
                        $object->room = $results['sala'][$h];
                        $object->lecturer = $results['wykladowca'][$h];


                        $objects[] = $object;

                    }}
                }
            }
        }

        return $objects;



    }

    public function workerscrap($pattern, $weeks)
    {

        $form = $this->crawler->filter('form')->form();
        $results['dzien'] = $results['godzina_start'] = $results['godzina_koniec'] = $results['wydzial'] = $results['sala'] = $results['przedmiot'] = [];
        foreach ($weeks['value'] as $week) {
            $this->crawler = $this->client->submit($form, ['tydzien' => $week]);
            $output = $this->crawler->filter('body');
            $html = $output->outerHtml();

            $start = stripos($html, '<body>');
            $end = stripos($html, '</body>', $offset = $start);
            $length = $end - $start;
            $htmlSection = substr($html, $start, $length);


            preg_match_all($pattern, $htmlSection, $matches);
            $results['dzien'] = array_merge($results['dzien'], $matches['dzien']);
            $results['godzina_start'] = array_merge($results['godzina_start'], $matches['godzinaStart']);
            $results['godzina_koniec'] = array_merge($results['godzina_koniec'], $matches['godzinaKoniec']);
            $results['wydzial'] = array_merge($results['wydzial'], $matches['wydzial']);
            $results['sala'] = array_merge($results['sala'], $matches['sala']);
            $results['przedmiot'] = array_merge($results['przedmiot'], $matches['przedmiot']);


        }
        $objects = [];
        $length = count($results['godzina_start']);

        for ($d = 0; $d < $length; $d += 8) {
            for ($g = $d+1; $g < $d+8; $g += 1) {
               // if (!($results['przedmiot'][$g] == "-")){
                    $object = new Wyklad();
                    $object->date = $results['dzien'][$d];
                    $object->timeStart = $results['godzina_start'][$g];
                    $object->timeEnd = $results['godzina_koniec'][$g];
                    $object->subject = $results['przedmiot'][$g];
                    $object->wydzial = $results['wydzial'][$g];
                    $object->room = $results['sala'][$g];


                    $objects[] = $object;
               // }
            }
        }


        return $results;
    }

    public function buildingscrap($source, $pattern)
    {
        $this->crawler = $this->client->request('GET', $source);

        $output = $this->crawler->filter('body');
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);
        $results['budynek'] = $matches['budynek'];

        $objects = [];


        $length = count($results['budynek']);


        for ($i = 0; $i < $length; $i++) {
            $object = new Budynek();


            $object->budynek = $results['budynek'][$i];


            $objects[] = $object;
        }

        return $objects;
    }

    public function roominbuildscrap($source, $pattern)
    {
        $this->crawler = $this->client->request('GET', $source);

        $output = $this->crawler->filter('body');
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);
        $inner_results['chwytak'] = $matches['chwytak'];

        $results_sorted = [];
        $inner_pattern = '/y (?<Budynek>.*?)<|<a href="checkSala\.php\?sala\=(?<IDconect>.*?)" target="_blank">(?<NRsali>.*?)</m';
        foreach ($inner_results['chwytak'] as $chwytak) {
            $temp_results['budynek'] = [];
            $results['IDconect'] = $results['NRsali'] = [];
            preg_match_all($inner_pattern, $chwytak, $matches);
            $temp_results['budynek'] = array_merge($temp_results['budynek'], $matches['Budynek']);
            $results['IDconect'] = array_merge($results['IDconect'], $matches['IDconect']);
            $results['NRsali'] = array_merge($results['NRsali'], $matches['NRsali']);
            $results['NRsali'][0] = $results['IDconect'][0] = $temp_results['budynek'][0];
            $results_sorted[] = $results;
        }



        $objects = [];


        foreach ($results_sorted as $array_build) {

            $length = count($array_build['NRsali']);


            for ($i = 1; $i < $length; $i++) {
                $object = new Budynek();
                $object->budynek=$array_build['NRsali'][0];
                $object->sala=$array_build['NRsali'][$i];
                $object->connect= $array_build['IDconect'][$i];

                $objects[] = $object;
            }
    }
        return $objects;
    }


}
