<?php

namespace App\DataFixtures;

use App\Entity\UserRecord;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserRecordFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userX = $manager->find(User::class, 1);

        $user = new UserRecord();
        $user->setCity('Bauru');
        $user->setCreatedAt(new \DateTime());
        $user->setFkUser($userX);
        $user->setJobType('Estagio');
        $user->setName('Emprego');
        $user->setRecord('Lorem ipsum dolor sit amet, consectetur adipiscing...');

        $manager->persist($user);
        $manager->flush();
    }
}
