<?php

namespace App\Tests\Service;

use App\Service\TheMovieDBService;
use App\ValueObject\Genre;
use App\ValueObject\Movie;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TheMovieDBServiceTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private CacheInterface $cache;
    private TheMovieDBService $service;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);

        $this->service = new TheMovieDBService(
            $this->httpClient,
            $this->cache,
            'api_key_test',
            'https://api.themoviedb.org',
            'https://image.tmdb.org/t/p/',
            'https://www.youtube.com/embed/',
        );
    }

    public function testGetGenresReturnsGenres(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn(
                json_encode(
                    [
                        'genres' => [
                            ['id' => 1, 'name' => 'Action'],
                        ],
                    ]
                )
            );
        $response->method('getStatusCode')->willReturn(200);

        $this->httpClient
            ->method('request')
            ->willReturn($response);

        // Config du cache
        $this->cache->method('get')
            ->willReturnCallback(function ($key, $callback) {
                return $callback($this->createMock(ItemInterface::class));
            });

        $genres = $this->service->getGenres();
        // dd($genres);
        $this->assertCount(1, $genres);
        $this->assertInstanceOf(Genre::class, $genres[0]);
        $this->assertEquals(1, $genres[0]->getId());
        $this->assertEquals('Action', $genres[0]->getName());
    }

    public function testGetMoviesByGenreReturnsMovies(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn(json_encode([
                'results' => [
                    [
                        'id' => 1,
                        'title' => 'Test Movie',
                        'overview' => 'Description of Test Movie',
                        'poster_path' => '/test.jpg',
                        'release_date' => '2021-05-01',
                        'vote_count' => 100,
                        'vote_average' => 8,
                        'backdrop_path' => '/backdrop.jpg',
                    ],
                ],
            ]));
        $response->method('getStatusCode')->willReturn(200);

        $this->httpClient
            ->method('request')
            ->willReturn($response);

        $movies = $this->service->getMoviesByGenre([28]);

        $this->assertArrayHasKey('all', $movies);
        $this->assertArrayHasKey('bestMovie', $movies);
        $this->assertCount(1, $movies['all']);
        $this->assertInstanceOf(Movie::class, $movies['bestMovie']);
        $this->assertEquals('Test Movie', $movies['bestMovie']->getTitle());
    }
}
