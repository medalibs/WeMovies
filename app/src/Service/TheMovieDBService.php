<?php

namespace App\Service;

use App\ValueObject\Genre;
use App\ValueObject\Movie;
use App\ValueObject\Trailer;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TheMovieDBService implements MovieServiceInterface
{
    public const API_GET_GENRES_LIST = '/3/genre/movie/list';
    public const API_GET_MOVIES_BY_GENRE = '/3/discover/movie';
    public const API_GET_MOVIE_BY_ID = '/3/movie/%d';

    public const API_GET_MOVIE_VIDEO_BY_ID = '/3/movie/%d/videos';
    public const API_LANG = 'en';
    public const API_IMG_SIZE = 'w200';
    public const API_BACK_IMG_SIZE = 'w1920_and_h800_multi_faces';
    private const CACHE_LIFETIME = 3600;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly CacheInterface $cache,
        private readonly string $theMovieDBApiKey,
        private readonly string $theMovieDBUrl,
        private readonly string $theMovieDBAUrlImg,
        private readonly string $youtubeUrl,
    ) {
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return $this->cache->get('themoviedb.genres.cache', function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_LIFETIME);

            $response = $this->httpClient->request(
                'GET',
                sprintf('%s%s', $this->theMovieDBUrl, self::API_GET_GENRES_LIST),
                [
                    'query' => [
                        'api_key' => $this->theMovieDBApiKey,
                        'language' => self::API_LANG,
                    ],
                ],
            );
            $data = json_decode($response->getContent(false), true);

            if (
                200 !== $response->getStatusCode() || !is_array($data) || !array_key_exists(
                    'genres',
                    $data
                ) || empty($data['genres'])
            ) {
                return [];
            }

            return $this->convertToGenresObject($data['genres']);
        });
    }

    /**
     * @param int[] $genres
     *
     * @return array{all: Movie[],bestMovie: Movie}|array{}
     */
    public function getMoviesByGenre(array $genres): array
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s%s', $this->theMovieDBUrl, self::API_GET_MOVIES_BY_GENRE),
            [
                'query' => [
                    'api_key' => $this->theMovieDBApiKey,
                    'with_genres' => [28],
                    'language' => self::API_LANG,
                    'sort_by' => 'popularity.desc',
                ],
            ],
        );

        $data = json_decode($response->getContent(false), true);

        if (
            200 !== $response->getStatusCode() || !is_array($data) || !array_key_exists('results', $data) || count(
                $data['results']
            ) < 1
        ) {
            return [];
        }

        return ['all' => $this->convertToMoviesObject($data), 'bestMovie' => $this->getBestMovie($data)];
    }

    public function getMovieById(int $id): ?Movie
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s%s', $this->theMovieDBUrl, sprintf(self::API_GET_MOVIE_BY_ID, $id)),
            [
                'query' => [
                    'api_key' => $this->theMovieDBApiKey,
                    'language' => self::API_LANG,
                ],
            ],
        );

        $data = json_decode($response->getContent(false), true);

        if (
            200 !== $response->getStatusCode() || !is_array($data)
        ) {
            return null;
        }

        return $this->getMovieDetails($data);
    }

    public function getMovieTrailerById(int $id): ?Trailer
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('%s%s', $this->theMovieDBUrl, sprintf(self::API_GET_MOVIE_VIDEO_BY_ID, $id)),
            [
                'query' => [
                    'api_key' => $this->theMovieDBApiKey,
                    'language' => self::API_LANG,
                ],
            ],
        );

        $data = $response->toArray();

        if (
            200 !== $response->getStatusCode() || !is_array($data) || !array_key_exists('results', $data) || count(
                $data['results']
            ) < 1
        ) {
            return null;
        }

        foreach ($data['results'] as $video) {
            if ('Trailer' === $video['type'] && 'YouTube' === $video['site']) {
                return new Trailer($video['name'], $this->youtubeUrl . $video['key']);
            }
        }

        return null;
    }

    /**
     * @param array{results: array<array<string>>} $data
     *
     * @return Movie[]
     */
    private function convertToMoviesObject(array $data): array
    {
        return array_map(fn ($result) => $this->getMovieDetails($result), $data['results']);
    }

    /**
     * @param array{results: array<array<string>>} $data
     */
    private function getBestMovie(array $data): Movie
    {
        return $this->getMovieDetails($data['results'][0]);
    }

    /**
     * @param array<string> $data
     */
    private function getMovieDetails(array $data): Movie
    {
        return new Movie(
            (int) $data['id'],
            $data['title'],
            $data['overview'],
            sprintf('%s%s%s', $this->theMovieDBAUrlImg, self::API_IMG_SIZE, $data['poster_path']),
            (int) (new \DateTime($data['release_date']))->format('Y'),
            (int) $data['vote_count'],
            (int) round((int) $data['vote_average'] * 0.5),
            sprintf('%s%s%s', $this->theMovieDBAUrlImg, self::API_BACK_IMG_SIZE, $data['backdrop_path']),
        );
    }

    /**
     * @param array<array<string>> $data
     *
     * @return Genre[]
     */
    private function convertToGenresObject(array $data): array
    {
        return array_map(fn ($result) => $this->getGenreDetails($result), $data);
    }

    /**
     * @param array<string> $data
     */
    private function getGenreDetails(array $data): Genre
    {
        return new Genre(
            (int) $data['id'],
            $data['name'],
        );
    }
}
