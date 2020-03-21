<?php

declare(strict_types=1);

namespace PhpInvest\Invest\Git;

final class Url
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getHost(): string
    {
        $host = parse_url($this->url, PHP_URL_HOST);

        if (!is_string($host)) {
            throw new \InvalidArgumentException(sprintf('Could not parse host from "%s"', $this->url));
        }

        return $host;
    }

    public function getOrganizationName(): string
    {
        $organizationName = $this->getSegment(1);

        if (null === $organizationName) {
            throw new \InvalidArgumentException(sprintf('Could not parse organization name from "%s"', $this->url));
        }

        return $organizationName;
    }

    public function getRepositoryName(): string
    {
        $organizationName = $this->getSegment(2);

        if (null === $organizationName) {
            throw new \InvalidArgumentException(sprintf('Could not parse repository name from "%s"', $this->url));
        }

        return $organizationName;
    }

    private function getSegment(int $position): ?string
    {
        $path = parse_url($this->url, PHP_URL_PATH);

        if (!is_string($path)) {
            return null;
        }

        return explode('/', $path)[$position] ?? null;
    }
}
