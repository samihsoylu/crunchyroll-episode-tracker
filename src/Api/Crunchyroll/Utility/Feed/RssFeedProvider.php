<?php

declare(strict_types=1);

namespace SamihSoylu\CrunchyrollSyncer\Api\Crunchyroll\Utility\Feed;

use Exception;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use SamihSoylu\CrunchyrollSyncer\Util\Contract\SleepHelperInterface;
use SimpleXMLElement;

final readonly class RssFeedProvider implements FeedProviderInterface
{
    private const MAX_ALLOWED_REQUEST_ATTEMPTS = 3;

    public function __construct(
        private string $rssFeedUrl,
        private GuzzleHttpClient $httpClient,
        private SleepHelperInterface $sleepHelper,
    ) {
    }

    public function getFeed(): SimpleXMLElement
    {
        $response = $this->getRssFeedResponse();

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Could not retrieve RSS feed');
        }

        $xml = simplexml_load_string($response->getBody()->getContents());
        if ($xml === false) {
            throw new \RuntimeException('Could not parse feed data, feed data is corrupt');
        }

        return $xml;
    }

    private function getRssFeedResponse(int $attempt = 1): ResponseInterface
    {
        try {
            return $this->httpClient->get($this->rssFeedUrl);
        } catch (ServerException $exception) {
            if ($this->isBadGateway($exception) && $this->isAllowedToRetry($attempt)) {
                $this->waitSeveralSeconds($attempt);

                return $this->getRssFeedResponse(++$attempt);
            }

            throw $exception;
        }
    }

    protected function isBadGateway(Exception|ServerException $exception): bool
    {
        return $exception->getCode() === 502;
    }

    protected function waitSeveralSeconds(int $attempt): void
    {
        $waitTime = pow(2, $attempt);
        $this->sleepHelper->sleep($waitTime);
    }

    protected function isAllowedToRetry(int $attempt): bool
    {
        return $attempt < self::MAX_ALLOWED_REQUEST_ATTEMPTS;
    }
}
