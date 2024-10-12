<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Chimpokodex;
use App\Entity\Chimpokomon;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;


    private $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $name = "admin";
        $users = [];
        for ($i = 0; $i < 1; $i++) {
            $user = new User();
            $user->setUsername($name)
                ->setPassword($this->userPasswordHasher->hashPassword($user, $name))
                ->setRoles(['ROLE_ADMIN'])
                // ->setStatus("on")
                //     ->setCreatedAt($date)
                //     ->setUpdatedAt($date)
            ;
            $manager->persist($user);

            $users[] = $user;
        }



        $defaultPvMin = 100;
        $defaultPvMax = 1000;
        $pokeIdMax = 152;
        $chimpokodexs = [];
        for ($i = 0; $i < 100; $i++) {

            $pvMax  = random_int($defaultPvMin + 1, $defaultPvMax);
            $pvMin  = random_int($defaultPvMin, $pvMax - 1);
            $dadId  = random_int(1, $pokeIdMax);
            $momId  = random_int(1, $pokeIdMax);
            $date = new \DateTime();
            $chimpokomon = new Chimpokodex();
            $chimpokomon->setName('Chimpokodexmon' . $i)
                ->setDadId($dadId)
                ->setMomId($momId)
                ->setPvMin($pvMin)
                ->setPvMax($pvMax)
                ->setStatus("on")
                ->setCreatedAt($date)
                ->setUpdatedAt($date);
            $manager->persist($chimpokomon);

            $chimpokodexs[] = $chimpokomon;
        }
        // $manager->flush();



        for ($i = 0; $i < 100; $i++) {

            $chimpokodex = $this->faker->randomElement($chimpokodexs);
            $pvMax  = random_int($chimpokodex->getPvMin(), $chimpokodex->getPvMax());
            $date = new \DateTime();
            $chimpokomon = new Chimpokomon();
            $chimpokomon->setName($chimpokodex->getName())
                ->setChimpokodex($chimpokodex)
                ->setPv($pvMax)
                ->setPvMax($pvMax)
                ->setStatus("on")
                ->setCreatedAt($date)
                ->setUpdatedAt($date);
            $manager->persist($chimpokomon);
        }


        $manager->flush();
    }
}
