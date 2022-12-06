<?php

namespace App\Scraper;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class Scraper
{
    private $client;
    private $crawler;

    public function __construct()
    {
        $this->client = new Client();

    }

    public function pracscrap($source, $pattern)
    {

        $this->crawler = $this->client->request('GET', $source);
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


        return $results;
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
}
