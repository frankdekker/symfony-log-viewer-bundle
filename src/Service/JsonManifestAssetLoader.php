<?php
declare(strict_types=1);

namespace FD\LogViewer\Service;

use JsonException;
use RuntimeException;

class JsonManifestAssetLoader
{
    /** @var array<int|string, mixed>|null */
    private ?array $manifestData = null;

    public function __construct(private readonly string $manifestPath)
    {
    }

    /**
     * @throws JsonException
     */
    public function getUrl(string $asset): string
    {
        if ($this->manifestData === null) {
            $data = json_decode((string)file_get_contents($this->manifestPath), true, 512, JSON_THROW_ON_ERROR);
            assert(is_array($data));
            $this->manifestData = $data;
        }

        $asset = $this->manifestData[$asset]['file'] ?? throw new RuntimeException('Asset manifest file does not exist: ' . $asset);

        return '/bundles/fdlogviewer/' . $asset;
    }
}
