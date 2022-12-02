<?php

namespace App\Scraper;

use Goutte\Client;


class Scraper
{
    public function initscrap($source, $pattern, $pattern2)
    {
        $client = new Client();
        $crawler = $client->request('GET', $source);
        $selector='body';
        $output = $crawler->filter($selector);
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');

        $end = stripos($html, '</body>', $offset = $start);

        $length = $end - $start;

        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);
        $results['value']=$matches['value'];
        preg_match_all($pattern2, $htmlSection, $matches);
        $results['tydzien']=$matches['tydzien'];




        return ($results);

    }
    public function scrap($source, $pattern, $weeks)
    {
        $client = new Client();
        $crawler = $client->request('GET', $source);
        $selector='body';
        $form = $crawler->filter('form')->form();
        $rok['dzien'] = array();
        $rok['hours'] = array();
        $rok['przedmiot'] = array();
        $rok['wykladowca'] = array();
        $rok['sala'] = array();
        foreach($weeks['value'] as $week){

        $crawler = $client->submit($form, array('dzien' => $week));
        $output = $crawler->filter($selector);
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');

        $end = stripos($html, '</body>', $offset = $start);

        $length = $end - $start;

        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);

        $rok['dzien'] = array_merge($rok['dzien'], $matches['dzien']);
        $rok['hours'] = array_merge($rok['hours'], $matches['hours']);
        $rok['przedmiot'] = array_merge($rok['przedmiot'], $matches['przedmiot']);
        $rok['wykladowca'] = array_merge($rok['wykladowca'], $matches['wykladowca']);
        $rok['sala'] = array_merge($rok['sala'], $matches['sala']);
        }


        return ($rok);

}

}
