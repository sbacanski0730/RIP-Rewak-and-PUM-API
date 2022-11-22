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
        var_dump($html);

        dd($html);



    return ($html);

}

}
