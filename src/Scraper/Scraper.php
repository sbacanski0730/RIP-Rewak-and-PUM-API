<?php

namespace App\Scraper;

use Goutte\Client;


class Scraper
{
    public function scrap($source)
    {
        $client = new Client();
        $crawler = $client->request('GET', $source);
        $links = $crawler->filterXPath('//tbody/tr/td[2][not(contains(@class,'.'nazwaSpecjalnosci'.'))]');
        var_dump($links);
        $links = $crawler->filterXPath('//tbody/tr/td[2][not(contains(@class,'.'nazwaSpecjalnosci'.'))]');
        dd($links);



    return ($subjects);

}

}
