<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * The test environment uses the src/Service/MovieMockService.php file to avoid calling the API of TheMovieDB site.
 * Config in conf/services_test.yaml.
 */
class HomePageControllerTest extends WebTestCase
{
    public function testHomePagePageRendersSuccessfully(): void
    {
        $client = static::createClient();

        try {
            $client->request('GET', '/');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $crawler = $client->getCrawler();

        $genres = $crawler->filter('.genre');
        $this->assertCount(2, $genres);

        $this->assertEquals('Action', $genres->eq(0)->text());
        $this->assertEquals('Comedy', $genres->eq(1)->text());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn () => null);

            restore_exception_handler();

            if (null === $previousHandler) {
                break;
            }

            restore_exception_handler();
        }
    }
}
