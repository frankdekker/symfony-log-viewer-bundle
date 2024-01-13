<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use JsonException;
use RuntimeException;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class JsonManifestVersionStrategy implements VersionStrategyInterface
{
    /** @var array<int|string, mixed>|null */
    private ?array $manifestData = null;

    public function __construct(private readonly string $manifestPath)
    {
    }

    /**
     * @throws JsonException
     */
    public function getVersion(string $path): string
    {
        return $this->applyVersion($path);
    }

    /**
     * @throws JsonException
     */
    public function applyVersion(string $path): string
    {
        if ($this->manifestData === null) {
            $data = json_decode((string)file_get_contents($this->manifestPath), true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($data));
            $this->manifestData = $data;
        }

        $path = $this->manifestData[$path]['file'] ?? throw new RuntimeException('Asset manifest file does not exist: ' . $path);

        return 'bundles/fdlogviewer/' . $path;
    }
}
