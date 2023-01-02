<?php

namespace App\DataFixtures;

use App\Entity\Settings;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $settings = new Settings();
        $settings->setPerPage(20);
        $settings->setParseUrl("https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json");
        $settings->setEmail("admin@admin.ru");

        $manager->persist($settings);
        $manager->flush();
    }
}
