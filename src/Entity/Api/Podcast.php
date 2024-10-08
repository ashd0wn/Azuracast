<?php

declare(strict_types=1);

namespace App\Entity\Api;

use App\Entity\Api\Traits\HasLinks;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Api_Podcast',
    type: 'object'
)]
final class Podcast
{
    use HasLinks;

    #[OA\Property]
    public string $id;

    #[OA\Property]
    public int $storage_location_id;

    #[OA\Property]
    public string $title;

    #[OA\Property]
    public ?string $link = null;

    #[OA\Property]
    public string $description;

    #[OA\Property]
    public string $description_short;

    #[OA\Property]
    public string $language;

    #[OA\Property]
    public string $language_name;

    #[OA\Property]
    public string $author;

    #[OA\Property]
    public string $email;

    #[OA\Property]
    public bool $has_custom_art = false;

    #[OA\Property]
    public string $art;

    #[OA\Property]
    public int $art_updated_at = 0;

    #[OA\Property]
    public bool $is_published = true;

    #[OA\Property]
    public int $episodes = 0;

    /**
     * @var PodcastCategory[]
     */
    #[OA\Property(
        type: 'array',
        items: new OA\Items(type: PodcastCategory::class)
    )]
    public array $categories = [];
}
