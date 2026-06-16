<?php

namespace App\Tests\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MovieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<Movie> */
    private EntityRepository $movieRepository;
    private string $path = '/movie/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->movieRepository = $this->manager->getRepository(Movie::class);

        foreach ($this->movieRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Movie index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'movie[title]' => 'Testing',
            'movie[director]' => 'Testing',
            'movie[releaseYear]' => 'Testing',
            'movie[synopsis]' => 'Testing',
        ]);

        self::assertResponseRedirects('/movie');

        self::assertSame(1, $this->movieRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }

    public function testShow(): void
    {
        $fixture = new Movie();
        $fixture->setTitle('My Title');
        $fixture->setDirector('My Title');
        $fixture->setReleaseYear('My Title');
        $fixture->setSynopsis('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Movie');

        // Use assertions to check that the properties are properly displayed.
        $this->markTestIncomplete('This test was generated');
    }

    public function testEdit(): void
    {
        $fixture = new Movie();
        $fixture->setTitle('Value');
        $fixture->setDirector('Value');
        $fixture->setReleaseYear('Value');
        $fixture->setSynopsis('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'movie[title]' => 'Something New',
            'movie[director]' => 'Something New',
            'movie[releaseYear]' => 'Something New',
            'movie[synopsis]' => 'Something New',
        ]);

        self::assertResponseRedirects('/movie');

        $fixture = $this->movieRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getDirector());
        self::assertSame('Something New', $fixture[0]->getReleaseYear());
        self::assertSame('Something New', $fixture[0]->getSynopsis());

        $this->markTestIncomplete('This test was generated');
    }

    public function testRemove(): void
    {
        $fixture = new Movie();
        $fixture->setTitle('Value');
        $fixture->setDirector('Value');
        $fixture->setReleaseYear('Value');
        $fixture->setSynopsis('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/movie');
        self::assertSame(0, $this->movieRepository->count([]));

        $this->markTestIncomplete('This test was generated');
    }
}
