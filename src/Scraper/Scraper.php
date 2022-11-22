<?php

namespace App\Scraper;

use Goutte\Client;


class Scraper
{
    public function scrap($source)
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

        preg_match_all('/<td class="nazwaDnia" colspan="4" style="font-size:13px">(?<dzien>.*?)<\/td>|<td class="godzina">(?<hours>.*?)<\/td><td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m', $htmlSection, $matches);

        dd($matches);

        dd($htmlSection);



    return ($html);

}

}
