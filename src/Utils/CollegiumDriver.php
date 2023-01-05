<?php


namespace App\Utils;

use Doctrine\DBAL\Driver;
use Goutte\Client;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Connection;

class CollegiumDriver implements Driver {
	private $client;
    private $crawler;

    public function __construct()
    {
        $this->client = new Client();

    }

	public function connect(array $params) {
		return new CollegiumConnection($this);
	}

    public function getDatabasePlatform() {
        return new CollegiumPlatform();
    }

    public function getSchemaManager(Connection $conn, AbstractPlatform $platform) {
        return new CollegiumSchemaManager(new Connection([], $this), $this->getDatabasePlatform());
    }

    /**
     * Gets the ExceptionConverter that can be used to convert driver-level exceptions into DBAL exceptions.
     */
    public function getExceptionConverter(): ExceptionConverter
    {
        return new CollegiumExceptionConverter();
    }

    public function getDepartments()
    {
        $body = $this->getBody('http://www.plan.pwsz.legnica.edu.pl/schedule_view.php?site=show_nauczyciel.php');
        $pattern = '/show_kierunek.php&amp;id=(?<numer_wydzialu>.*?)">(?<nazwa_wydzialu>.*?)</m';

        preg_match_all($pattern, $body, $matches);
        $results['name'] = $matches['nazwa_wydzialu'];
        $results['number'] = $matches['numer_wydzialu'];


        $objects = [];


        $length = count($results['name']);

        for ($i = 0; $i < $length; $i++) {
            $objects[] = (object) [
                'id' => $results['number'][$i],
                'name' => $results['name'][$i]
            ];
        }

        return $objects;
    }

    public function getDepartmentsCount()
    {
        $rows = $this->getDepartments();

        return (object) ['sclr_0' => count($rows)];
    }

    private function getBody($url) {
        $this->crawler = $this->client->request('GET', $url);
        $output = $this->crawler->filter('body');
        $html = $output->outerHtml();


        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);
 
        return $htmlSection;
    }
}