<?php

namespace App\Repository;

use App\Enum\ClothingType;
use App\Enum\Color;
use App\Service\OllamaApi;
use App\Service\OllamaMessage;
use App\Service\OllamaRole;
use App\Entity\Clothing;
use App\Entity\User;

class SearchService
{
  public function __construct(
    private OllamaApi $ollama,
    private ClothingRepository $clothingRepository,
    private UserRepository $userRepository
  ) {}

  /**
   * Search for clothing items in an image
   * @param string $image
   * @return Clothing[]|null
   */
  public function imageSearch(string $image): array|null
  {
    $base64Image = base64_encode($image);

    $colors = implode(' - ', array_map(fn(Color $color) => $color->value, Color::cases()));
    $types = implode(' - ', array_map(fn(ClothingType $type) => $type->value, ClothingType::cases()));
    $clothingInfos = $this->ollama->chat(
      [
        new OllamaMessage(
          '
                    Describe all clothing items you can see.

                    The possible colors are:
                    ' . $colors . '

                    The possible types are:
                    ' . $types . '
                    ',
          OllamaRole::USER,
          ['images' => [$base64Image]]
        )
      ],
      'llama3.2-vision',
      [
        'type' => 'array',
        'items' => [
          'type' => 'object',
          'properties' => [
            'color' => ['type' => 'string'],
            'type' => ['type' => 'string'],
          ],
          'required' => ['color', 'type'],
        ],
      ]
    );

    $clothingInfos = json_decode($clothingInfos, true);
    if ($clothingInfos === null) {
      return null;
    }

    /** @var Clothing[] $clothings */
    $clothings = [];

    foreach ($clothingInfos as $clothingInfo) {
      $clothings = array_merge(
        $clothings,
        $this->clothingRepository->findByFields([
          'color' => Color::from($clothingInfo['color']),
          'type' => ClothingType::from($clothingInfo['type']),
        ]) ?? []
      );
    }

    return $clothings;
  }

  /**
   * Search for users or clothing items
   * @param string $query
   * @return Clothing[]|User[]
   */
  public function textSearch(string $query): array
  {
    return array_merge(
      $this->userRepository->searchByText($query),
      $this->clothingRepository->searchByText($query)
    );
  }
}
