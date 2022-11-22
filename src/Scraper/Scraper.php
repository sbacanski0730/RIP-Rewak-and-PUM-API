<?php

namespace App\Scraper;

use Goutte\Client;


class Scraper
{
    public function scrap($source, $pattern)
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

        dd($matches);

        dd($htmlSection);



    return ($html);

}

}
