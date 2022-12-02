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
        $output = $crawler->filter($selector);
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');

        $end = stripos($html, '</body>', $offset = $start);

        $length = $end - $start;

        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);




        return ($matches);

}

}
