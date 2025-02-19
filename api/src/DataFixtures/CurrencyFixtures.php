<?php

namespace App\DataFixtures;

use App\Entity\Currency\Currency;
use App\Entity\Currency\Field\Code;
use App\Entity\Currency\Field\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture
{
    private const array CODES = Code::CODES;

    public function load(ObjectManager $manager): void
    {
        foreach (self::CODES as $code) {
            $currency = new Currency(Id::create(), new Code($code));
            $manager->persist($currency);
        }

        $manager->flush();
    }
}
