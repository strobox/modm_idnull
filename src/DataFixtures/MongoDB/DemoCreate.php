<?php

namespace App\DataFixtures\MongoDB;

use App\Document\Customer;
use App\Document\Folder;
use App\Repository\FolderRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoCode;
use MongoDB\BSON\Javascript;
use MongoDB\BSON\ObjectId;
use MongoRegex;

// ...

class DemoCreate extends AbstractFixture
{


    public function load(ObjectManager $manager)
    {
        $xxxyyy = new Folder("xxxyyy");

        $abcxyz = new Customer();
        $abcxyz->setNickname('abcxyz');
        $abcxyz->setEmail('tut@by.ru');

        $manager->persist($abcxyz);

        $xxxyyy->addItem($abcxyz);

        $manager->persist($xxxyyy);
        $manager->flush();

        $manager->find(Customer::class,$abcxyz->getId());
        fwrite(STDOUT,'ok insert: ' . PHP_EOL . $abcxyz->getId() . PHP_EOL);
        shell_exec("open http://localhost:8080/test/odm?customer_id=".$abcxyz->getId());
    }

}