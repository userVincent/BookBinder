<?php

namespace App\DataFixtures;

use App\Entity\Library;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LibraryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $spreadsheet = IOFactory::load('C:\Users\vince\..School\3rdYear\webtech\LibraryData.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        foreach ($rows as $row) {
            $library = new Library();
            $library->setName($row[2]); 
            $library->setType($row[3]);
            $library->setLongitude(0);
            $library->setLatitude(0);
            $library->setTown($row[0]);
            $library->setPostalCode($row[8]);
            $library->setStreetName($row[5]);
            $library->setHouseNumber($row[6]);
            $library->setYear($row[1]);
            // Continue setting fields according to your Excel data

            $manager->persist($library);
        }

        $manager->flush();
    }
}
