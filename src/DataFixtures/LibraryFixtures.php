<?php

namespace App\DataFixtures;

use App\Entity\Library;
use App\Entity\Staff;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LibraryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $handle = fopen(__DIR__."/data/LibraryData.csv", "r");
        if ($handle === false) {
            throw new \Exception('Failed to open the CSV file.');
        }
        // read first line with headers
        $headers = fgetcsv($handle, 1010, ",");
        // read rest of file and create entities for every line
        while (($data = fgetcsv($handle, 1010, ",")) !== FALSE) {
            $library = new Library();
            $library->setName($data[2]);
            $library->setType($data[3]);
            $library->setLongitude(0);
            $library->setLatitude(0);
            $library->setTown($data[0]);
            $library->setPostalCode($data[6]);
            $library->setStreetName($data[4]);
            $library->setHouseNumber($data[5]);
            $library->setYear($data[1]);
            // store reference to object based on name (email-string)
            $this->setReference($library->getName(), $library);
            $manager->persist($library);
        }


        fclose($handle);
        $manager->flush();
    }
}
