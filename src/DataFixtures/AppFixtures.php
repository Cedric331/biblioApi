<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Pret;
use App\Entity\Livre;
use App\Entity\Adherent;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
   protected $entity;
   protected $faker;

   public function __construct(EntityManagerInterface $entity)
   {
      $this->entity = $entity;
      $this->faker = Factory::create("fr_FR");
   }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    /**
     * Génération des adhérents
     *
     * @return void
     */
    public function loadAdherent()
    {
       $genre = ['male', 'female'];
       $commune = [
         "78003", "78005", "78006", "78007", "78009", "78010", "78013", "78015", "78020", "78029",
         "78030", "78031", "78033", "78034", "78036", "78043", "78048", "78049", "78050", "78053", "78057",
         "78062", "78068", "78070", "78071", "78072", "78073", "78076", "78077", "78082", "78084", "78087",
         "78089", "78090", "78092", "78096", "78104", "78107", "78108", "78113", "78117", "78118"
     ];
       for ($i=0; $i < 25; $i++) { 
         $adherent = new Adherent();
         $adherent->setNom($this->faker->lastName())
                  ->setPrenom($this->faker->firstName($genre[mt_rand(0,1)]))
                  ->setAdresse($this->faker->streetAdress())
                  ->setEmail(strtolower($adherent->getNom()).'@gmail.com')
                  ->setPassword($adherent->getNom())
                  ->setPhone($this->faker->phoneNumber())
                  ->setCodeCommune($commune[mt_rand(0, count($commune)-1)]);
         $this->entity->persist($adherent);
       };

       $adherent->setNom("Lima")
                  ->setPrenom("Cedric")
                  ->setAdresse("rue lilas")
                  ->setEmail('admin@gmail.com')
                  ->setPassword('password')
                  ->setPhone($this->faker->phoneNumber())
                  ->setCodeCommune($commune[mt_rand(0, count($commune)-1)]);
         $this->entity->persist($adherent);
         $this->entity->flush();
    }

    /**
     * Génération des prêts
     *
     * @return void
     */
    public function loadPret()
    {
      for ($i=0; $i < 25; $i++) { 
         $pret = new Pret();
         $pret->setDatePret($this->faker->dateTimeBetween('-3 months', 'now'));
            $timestamp = date('Y-m-d H:m:s', strtotime("15 days",$pret->getDatePret()->getTimestamp()));
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
            if(rand(1,3) == 1){
               $pret->setDateRetour($this->faker->dateTimeInInterval($pret->getDatePret(), '+30 days'));
            };
            $pret->setDateRetourPrevue($date)
                  ->setLivre($this->entity->getRepository(Livre::class)->find(rand(1,49)))
                  ->setAdherent($this->entity->getRepository(Adherent::class)->find(rand(1,25)));
         $this->entity->persist($pret);
       };

         $this->entity->flush();
    }
}
