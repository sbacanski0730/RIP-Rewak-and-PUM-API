<?php

namespace App\Scraper;

use Goutte\Client;


class Scraper
{
    public function scrap($source, $pattern, $weeks = '19-09-2022')
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
