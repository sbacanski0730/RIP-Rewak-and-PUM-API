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

    public function scrap($pattern, $weeks)
    {
        $form = $this->crawler->filter('form')->form();
        $rok['dzien'] = $rok['hours'] = $rok['przedmiot'] = $rok['wykladowca'] = $rok['sala'] = [];

        foreach ($weeks['value'] as $week) {
            $this->crawler = $this->client->submit($form, ['dzien' => $week]);
            $output = $this->crawler->filter('body');
            $html = $output->outerHtml();

            $start = stripos($html, '<body>');
            $end = stripos($html, '</body>', $offset = $start);
            $length = $end - $start;
            $htmlSection = substr($html, $start, $length);

            preg_match_all($pattern, $htmlSection, $matches);
            $filter = array_filter($matches['dzien'], function ($item) {
                return strlen($item) > 0;
            });
            $rok['dzien'] = array_merge($rok['dzien'], $filter);
            $filter = array_filter($matches['hours'], function ($item) {
                return strlen($item) > 0;
            });
            $rok['hours'] = array_merge($rok['hours'], $filter);
            $filter = array_filter($matches['przedmiot'], function ($item) {
                return strlen($item) > 0;
            });
            $rok['przedmiot'] = array_merge($rok['przedmiot'], $filter);
            $filter = array_filter($matches['wykladowca'], function ($item) {
                return strlen($item) > 0;
            });
            $rok['wykladowca'] = array_merge($rok['wykladowca'], $filter);
            $filter = array_filter($matches['sala'], function ($item) {
                return strlen($item) > 0;
            });
            $rok['sala'] = array_merge($rok['sala'], $filter);
        }
        return $rok;
    }

    public function workerscrap($pattern, $weeks)
    {

        $form = $this->crawler->filter('form')->form();
        $results['dzien'] = $results['godzina'] = $results['wydzial'] = $results['sala'] = [];
        foreach ($weeks['value'] as $week) {
            $this->crawler = $this->client->submit($form, ['tydzien' => $week]);
            $output = $this->crawler->filter('body');
            $html = $output->outerHtml();

            $start = stripos($html, '<body>');
            $end = stripos($html, '</body>', $offset = $start);
            $length = $end - $start;
            $htmlSection = substr($html, $start, $length);


            preg_match_all($pattern, $htmlSection, $matches);
            dd($matches);
            $filter = array_filter($matches['dzien'], function ($item) {
                return strlen($item) > 0;
            });
            $results['dzien'] = array_merge($results['dzien'], $filter);
            $filter = array_filter($matches['godzina'], function ($item) {
                return strlen($item) > 0;
            });
            $results['godzina'] = array_merge($results['godzina'], $filter);
            $filter = array_filter($matches['wydzial'], function ($item) {
                return strlen($item) > 0;
            });
            $results['wydzial'] = array_merge($results['wydzial'], $filter);
            $filter = array_filter($matches['sala'], function ($item) {
                return strlen($item) > 0;
            });
            $results['sala'] = array_merge($results['sala'], $filter);
        }






        return $results;
    }


}
