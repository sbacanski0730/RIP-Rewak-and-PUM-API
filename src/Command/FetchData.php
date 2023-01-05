<?php

namespace App\Command;

use DateTime;
use Goutte\Client;
use App\Entity\Department;
use App\Entity\Building;
use App\Entity\Worker;
use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Room;
use App\Entity\Course;

use App\Repository\BuildingRepository;
use App\Repository\CourseRepository;
use App\Repository\DepartmentRepository;
use App\Repository\EventRepository;
use App\Repository\GroupRepository;
use App\Repository\RoomRepository;
use App\Repository\WorkerRepository;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:data:fetch', 'Fetches data from remote knowledge base')]
class FetchData extends Command
{
    static private $apiBaseUrl = 'http://www.plan.pwsz.legnica.edu.pl/';

    private $client;

    private $buildingRepository;
    private $courseRepository;
    private $departmentRepository;
    private $eventRepository;
    private $groupRepository;
    private $roomRepository;
    private $workerRepository;

    public function __construct(
        BuildingRepository $buildingRepository,
        CourseRepository $courseRepository,
        DepartmentRepository $departmentRepository,
        EventRepository $eventRepository,
        GroupRepository $groupRepository,
        RoomRepository $roomRepository,
        WorkerRepository $workerRepository,
    ) {
        $this->client = new Client();

        $this->buildingRepository = $buildingRepository;
        $this->courseRepository = $courseRepository;
        $this->departmentRepository = $departmentRepository;
        $this->eventRepository = $eventRepository;
        $this->roomRepository = $roomRepository;
        $this->workerRepository = $workerRepository;
        $this->groupRepository = $groupRepository;

        parent::__construct();
    }

	protected function execute(InputInterface $input, OutputInterface $output): int
    {
        \ini_set('memory_limit', '512M');

        $io = new SymfonyStyle($input, $output);

        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


        // echo json_encode($this->workerRepository->find(412)->getDepartments(), JSON_PRETTY_PRINT);

        $this->clearDatabase();

        // Fetch departments
        $departments = $this->fetchDepartments();
        $departmentEntities = [];
    
        $io->text(json_encode($departments[0], JSON_PRETTY_PRINT));
        
        foreach ($departments as $department) {
            $entity = new Department();

            $entity
                ->setId($department->id)
                ->setName($department->name);
    
            $this->departmentRepository->save($entity);

            $departmentEntities[$department->id] = $entity;
        }
        
        // Fetch workers
        $_workers = $this->fetchWorkers();
        $workers = [];
        $customMeetings = 0;

        $io->text(json_encode($_workers[0], JSON_PRETTY_PRINT));

        $otherWorker = new Worker();

        $otherWorker->setId(9999)->setName("Różni");
        $this->workerRepository->save($otherWorker);

        foreach ($departments as $department) {
            $departmentEntities[$department->id]->addWorker($otherWorker);
            $this->departmentRepository->save($departmentEntities[$department->id]);
        }

        foreach ($_workers as $worker) {
            if (!array_key_exists($worker->id, $workers)) {
                $workers[$worker->id] = (object) ['name' => $worker->name, 'departments' => []];
            }

            $workers[$worker->id]->departments[] = $worker->department;
        }
        
        unset($_workers);

        foreach ($workers as $id => $worker) {
            $entity = new Worker();
            $_departments = [];

            $entity
                ->setId($id)
                ->setName(str_replace("  ", " ", $worker->name));


            foreach ($worker->departments as $departmentId) {
                // $io->text(json_encode(strval($_department->getId()), JSON_PRETTY_PRINT));
                $_departments[] = $departmentEntities[$departmentId];

                $departmentEntities[$departmentId]->addWorker($entity);
                $this->departmentRepository->save($departmentEntities[$departmentId]);
            }
    

            $entity
                ->setDepartments($_departments);
            
            $this->workerRepository->save($entity);
        }

        // Fetch buildings

        $buildings = $this->fetchBuildings();
        $io->text(json_encode($buildings[0], JSON_PRETTY_PRINT));
        $buildingEntities = [];

        foreach($buildings as $building){

            $entity = new Building();

            $entity
                ->setId($building->id)
                ->setName($building->name);

            $this->buildingRepository->save($entity);

            $buildingEntities[$building->id] = $entity;
        }

        $onlineBuilding = new Building();

        $onlineBuilding->setId('online')->setName('online @');
        $this->buildingRepository->save($onlineBuilding);

        // Fetch rooms

        $rooms = $this->fetchRooms();
        $io->text(json_encode($rooms[0], JSON_PRETTY_PRINT));

        foreach($rooms as $room){

            $entity = new Room();

            $entity
                ->setId($room->id)
                ->setName($room->name)
                
                ->setBuilding($buildingEntities[$room->building]);


            $buildingEntities[$room->building]->addRoom($entity);

            $this->roomRepository->save($entity);
        }

        // Fetch courses
        $courses = [];
        $courseEntities = [];

        foreach ($departmentEntities as $department) {
            $departmentCourses = $this->fetchCourses($department->getId());

            foreach($departmentCourses as $course) {
                $course->departmentId = $department->getId();
            }

            $courses = array_merge($courses, $departmentCourses);
        }

        $io->text(json_encode($courses, JSON_PRETTY_PRINT));


        foreach($courses as $course){
            $entity = new Course();

            $entity
                ->setId($course->id)
                ->setName($course->name)
                ->setDepartment($departmentEntities[$course->departmentId]);

            $departmentEntities[$course->departmentId]->addCourse($entity);
            $this->courseRepository->save($entity);
            $this->departmentRepository->save($departmentEntities[$course->departmentId]);
            $courseEntities[$course->id] = $entity;
        }


        $this->flushDatabase();

        // Fetch schedule
        $events = [];
        $counter = 0;

        // todo zmieniac zakres slice zeby namierzyc na ktorym sie sypie
        // foreach(array_slice($courseEntities, 29, 1) as $course){ //"s3PAM"
        // foreach(array_slice($courseEntities, 42, 1) as $course){ //"s2ZPK"
        // foreach(array_slice($courseEntities, 44, 1) as $course){ //"s2PR"
        // foreach(array_slice($courseEntities, 50, 1) as $course){ //"n2ZIP"
        // foreach(array_slice($courseEntities, 54, 2) as $course){ //"s1ZIP"  "s2ZIP"
        // foreach(array_slice($courseEntities, 77, 1) as $course){ //"s2ZK"
        // foreach(array_slice($courseEntities, 80, 3) as $course){ //"s2JMPED"  "s3JMPED"  "s4JMPED"

            foreach(array_slice($courseEntities, 29, 1) as $course){
            $io->text('Fetching: '.$course->getName().' ('.++$counter.' of '.count($courseEntities).').');

            $courseEvents = $this->fetchSchedule($course->getId());

            // foreach($courseEvents as $event) {
            //     $event->departmentId = $course->getDepartment();
            // }

            $events = array_merge($events, $courseEvents);
        }

        $groups = [];
        $io->text(json_encode($events[0], JSON_PRETTY_PRINT));

        $onlineRoomIdIdx = 4000;


        foreach ($events as $event) {
            $entity = new Event();
            preg_match('/.*\s(?<startDay>[\d\.]+)$/', $event->date, $matches);
            $startTime = DateTime::createFromFormat('d.m.Y H:i', $matches['startDay'] . ' ' . $event->timeStart);
            $endTime = DateTime::createFromFormat('d.m.Y H:i', $matches['startDay'] . ' ' . $event->timeEnd);
            // echo 'worker to find: '.$event->lecturer ."\r\n";
            $worker = $this->workerRepository->findByName($event->lecturer);

            // echo 'room to find: '.$event->room ."\r\n";
            $room = $this->roomRepository->findOneByName($event->room);

            if (is_null($worker)) {
                //todo nie pokazywac echo jak nazwa zaczyna sie od Legenda
                // $io->text(json_encode($event, JSON_PRETTY_PRINT));
                if ($event->lecturer != 'Legenda1'){
                    if ($event->lecturer != 'Legenda2'){
                        if ($event->lecturer != 'Legenda3'){
                            echo 'unable to find: '.$event->lecturer . "\r\n";
                            }
                        }
                }
                $worker = $otherWorker;
            }

            if (is_null($room)) {
                $onlineRoom = new Room();

                $onlineRoom
                    ->setId(++$onlineRoomIdIdx)
                    ->setName($event->room)
                    ->setBuilding($onlineBuilding);
    
                $onlineBuilding->addRoom($onlineRoom);
                $this->roomRepository->save($onlineRoom);
                $this->buildingRepository->save($onlineBuilding);

                $room = $onlineRoom;
            }


            if (!array_key_exists($event->group, $groups)) {
                // echo 'group to find: '.$event->group ."\r\n";
                $groupEntity = new Group();

                // echo 'group to find: '.$event->group ."\r\n";
                $course = $this->courseRepository->findOneByGroup($event->group);

                $groupEntity->setId($event->group);
                $groupEntity->setCourse($course);
                $this->groupRepository->save($groupEntity);
                $groups[$event->group] = $groupEntity;
            }

            $worker->addEvent($entity);
            $this->workerRepository->save($worker);

            $entity
                ->setSubject($event->subject)
                ->setGroup($groups[$event->group])
                ->setStartTime($startTime)
                ->setRoom($room)
                ->setWorker($worker)
                ->setEndTime($endTime);

            
            $this->eventRepository->save($entity);
            // echo 'date: '.$startTime->format('Y-m-d H:i:s')."\r\n";
            // echo 'worker: '.$event->lecturer .' -> ' . $worker->getId()."\r\n";
            // echo 'room: '.$event->room .' -> ' . $room->getId()."\r\n";
        }

        $this->flushDatabase();
         
        return Command::SUCCESS;
    }

    private function clearDatabase()
    {
        $this->eventRepository->deleteAll();
        $this->groupRepository->deleteAll();
        
        $this->courseRepository->deleteAll();

        $this->departmentRepository->deleteAll();
        $this->workerRepository->deleteAll();
        $this->roomRepository->deleteAll();
        $this->buildingRepository->deleteAll();
    }

    private function flushDatabase()
    {
        $this->departmentRepository->flush();
        $this->workerRepository->flush();
        $this->roomRepository->flush();
        $this->buildingRepository->flush();
        $this->groupRepository->flush();
        $this->courseRepository->flush();
        $this->eventRepository->flush();
    }
    private function fetchDepartments()
    {
        $endpoint = self::$apiBaseUrl.'/schedule_view.php?site=show_nauczyciel.php';

        $pattern = '/show_kierunek.php\&id=(?<numer_wydzialu>.*?)">(?<nazwa_wydzialu>.*?)</m';


        $html = file_get_contents($endpoint);
        $html = mb_convert_encoding($html, 'utf-8', 'iso-8859-2');

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;

        preg_match_all($pattern, $html, $matches);

        $results['nazwa'] = $matches['nazwa_wydzialu'];
        $results['numer'] = $matches['numer_wydzialu'];


        $length = count($results['nazwa']);

        $objects = [];

        for ($i = 0; $i < $length; $i++) {
            $objects[] = (object) ['name' => $results['nazwa'][$i], 'id' => $results['numer'][$i]];
        }

        return $objects;
    }

    private function fetchWorkers()
    {
        $endpoint = self::$apiBaseUrl.'/schedule_view.php?site=show_nauczyciel.php';
        $pattern = '/checkNauczycielAll\.php\?pracownik=(?<numer_pracownik>.*?)\&wydzial=(?<wydzial>.*?) target="_blank">((?<pracownik>.*?))\s?</mi';
       

        $html = file_get_contents($endpoint);
        $html = mb_convert_encoding($html, 'utf-8', 'iso-8859-2');

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
            $objects[] = (object) [
                'name' => $results['name'][$i],
                'id' => $results['numer_pracownik'][$i],
                'department' => $results['wydzial_pracownik'][$i]
            ];
        }
        return $objects;
    }

    private function fetchBuildings() {
        $endpoint = self::$apiBaseUrl.'/schedule_view.php?site=show_sala.php';
        $pattern = '/#">(?<budynek>.*?)</m';

        $html = file_get_contents($endpoint);
        $html = mb_convert_encoding($html, 'utf-8', 'iso-8859-2');

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);
        $results['budynek'] = $matches['budynek'];

        $objects = [];

        $length = count($results['budynek']);


        for ($i = 0; $i < $length; $i++) {
            $objects[] = (object) [
                'id' => str_replace('Bud', 'bud', $results['budynek'][$i]),
                'name' => $results['budynek'][$i],
            ];
        }

        return $objects;
    }

    public function fetchRooms()
    {
        $endpoint = self::$apiBaseUrl.'/schedule_view.php?site=show_sala.php';
        $pattern = '/<div><a href=checkBudynek\.php\?(?<chwytak>.*?)<\/li>/m';

        $html = file_get_contents($endpoint);
        $html = mb_convert_encoding($html, 'utf-8', 'iso-8859-2');

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);
        $htmlSection = trim(preg_replace('/\s+/', ' ', $htmlSection));

        preg_match_all($pattern, $htmlSection, $matches);
        $inner_results['chwytak'] = $matches['chwytak'];

        $results_sorted = [];
        $inner_pattern = '/y (?<Budynek>.*?)<|<a href=checkSala\.php\?sala\=(?<IDconect>.*?) target="_blank">(?<NRsali>.*?)</mi';
        foreach ($inner_results['chwytak'] as $chwytak) {
            $temp_results['budynek'] = [];
            $results['IDconect'] = $results['NRsali'] = [];
            preg_match_all($inner_pattern, $chwytak, $matches);
            $temp_results['budynek'] = array_merge($temp_results['budynek'], $matches['Budynek']);
            $results['IDconect'] = array_merge($results['IDconect'], $matches['IDconect']);
            $results['NRsali'] = array_merge($results['NRsali'], $matches['NRsali']);
            $results['NRsali'][0] = $results['IDconect'][0] = $temp_results['budynek'][0];
            $results_sorted[] = $results;
        }

        $objects = [];


        foreach ($results_sorted as $array_build) {

            $length = count($array_build['NRsali']);


            for ($i = 1; $i < $length; $i++) {
                $objects[] = (object) [
                    'building' => $array_build['NRsali'][0],
                    'name' => $array_build['NRsali'][$i],
                    'id' => $array_build['IDconect'][$i],
                ];
            }
        }

        return $objects;
    }

    public function fetchCourses($departmentId) {
        $endpoint = self::$apiBaseUrl.'/schedule_view.php?site=show_kierunek.php&id='.$departmentId;

        $html = file_get_contents($endpoint);
        $html = mb_convert_encoding($html, 'utf-8', 'iso-8859-2');
        
        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);

        $pattern = '/target="_blank">(?<courseName>.*?)<\/a>\|<a href="?checkSpecjalnoscStac\.php\?specjalnosc=(?<courseId>.*?)"?>/mi';
        preg_match_all($pattern, $htmlSection, $matches);
        $results['courseName'] = $matches['courseName'];
        $results['courseId'] = $matches['courseId'];

        $objects = [];

        $length = count($results['courseName']);

        for ($i = 0; $i < $length; $i++) {
            $objects[] = (object) [
                'name' => $results['courseName'][$i],
                'id' => $results['courseId'][$i]
            ];
        }

        return $objects;
    }

    private function fetchSchedule($_courseId) {
        $courseId = mb_convert_encoding($_courseId, 'iso-8859-2', 'utf-8');
        $endpoint = self::$apiBaseUrl.'/checkSpecjalnosc.php?specjalnosc='.$courseId;
        $pattern = '/<option value="(?<value>.*?)"/m';
        $pattern2 = '/\((?<tydzien>.\d.*?)\)/m';  

        $crawler = $this->client->request('GET', $endpoint);

        $output = $crawler->filter('body');
        $html = $output->outerHtml();

        $start = stripos($html, '<body>');
        $end = stripos($html, '</body>', $offset = $start);
        $length = $end - $start;
        $htmlSection = substr($html, $start, $length);

        preg_match_all($pattern, $htmlSection, $matches);
        $results['value'] = $matches['value'];
        preg_match_all($pattern2, $htmlSection, $matches);
        $results['tydzien'] = $matches['tydzien'];
        
        $weeks = $results;

        $pattern = '/class="nazwaSpecjalnosci" colspan="3">(?<grupa>.*?)<\/td>/m';
        $pattern2 = '/" style="font-size:13px">(?<dzien>.*?)<\/td>/m';
        $pattern3 = '/<td class="godzina">(?<godzinaStart>.*?)-(?<godzinaKoniec>.*?)<\/td>|<td class="test">(?<przedmiot>.*?)<br><\/td><td class="test">(?<wykladowca>.*?)<br><\/td><td class="test2">(?<sala>.*?)<br><\/td>/m';

        $form = $crawler->filter('form')->form();
        $objects = [];

        foreach ($weeks['value'] as $week) {
            $results['dzien'] = $results['godzina_start'] = $results['godzina_koniec'] = $results['przedmiot'] = $results['wykladowca'] = $results['sala'] = $results['grupa'] = [];;
            $crawler = $this->client->submit($form, ['dzien' => $week]);
            $output = $crawler->filter('body');
            $html = $output->outerHtml();

            $start = stripos($html, '<body>');
            $end = stripos($html, '</body>', $offset = $start);
            $length = $end - $start;
            $htmlSection = substr($html, $start, $length);

            preg_match_all($pattern, $htmlSection, $matches);
            preg_match_all($pattern2, $htmlSection, $matches2);
            preg_match_all($pattern3, $htmlSection, $matches3);

            $results['grupa'] = array_merge($results['grupa'], $matches['grupa']);
            $results['dzien'] = array_merge($results['dzien'], $matches2['dzien']);
            $results['godzina_start'] = array_merge($results['godzina_start'], $matches3['godzinaStart']);
            $results['godzina_koniec'] = array_merge($results['godzina_koniec'], $matches3['godzinaKoniec']);
            $results['przedmiot'] = array_merge($results['przedmiot'], $matches3['przedmiot']);
            $results['wykladowca'] = array_merge($results['wykladowca'], $matches3['wykladowca']);
            $results['sala'] = array_merge($results['sala'], $matches3['sala']);


            $length = count($results['grupa']);

            $lenghth3 = count($results['dzien']);

            for ($g = 0; $g < $length; $g ++) {
                $h=1+$g;
                for ($d = 0; $d<$lenghth3; $d++){
                    for (;$h < ($d+1)*((1+$length)*7) ; $h += (1+$length)) {
                        if (!(($results['przedmiot'][$h] == "-") OR ($results['przedmiot'][$h] == "")) ){
                            $objects[] = (object) [
                                'date' => $results['dzien'][$d],
                                'group' => $results['grupa'][$g],
                                'timeStart' => $results['godzina_start'][$h-1-$g],
                                'timeEnd' => $results['godzina_koniec'][$h-1-$g],
                                'subject' => $results['przedmiot'][$h],
                                'room' => $results['sala'][$h],
                                'lecturer' => $results['wykladowca'][$h],
                            ];
                        }
                    }
                }
            }
        }

        return $objects;
    }
}