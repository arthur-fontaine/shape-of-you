<?php

namespace App\Service;

use App\Enum\ClothingType;
use App\Enum\Color;
use App\Service\OpenAiApi;
use App\Service\OpenAiMessage;
use App\Service\OpenAiRole;
use App\Entity\Clothing;
use App\Entity\User;
use App\Repository\ClothingRepository;
use App\Repository\UserRepository;

class SearchService
{
  public function __construct(
    private OpenAiApi $openAi,
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
    $clothingInfos = $this->openAi->chat(
      [
        new OpenAiMessage(
          '
                    Describe all clothing items you can see.

                    The possible colors are:
                    ' . $colors . '

                    The possible types are:
                    ' . $types . '
                    ',
          OpenAiRole::USER,
          [
            [
              'type' => 'image_url',
              'image_url' => [
                'url' => 'data:image/jpeg;base64,' . $base64Image,
              ],
            ]
          ]
        )
      ],
      $_ENV['VISION_MODEL'],
      [
        'type' => 'json_schema',
        'json_schema' => [
          'name' => 'clothings',
          'strict' => true,
          'schema' => [
            'type' => 'array',
            'items' => [
              'type' => 'object',
              'properties' => [
                'color' => ['type' => 'string'],
                'type' => ['type' => 'string'],
              ],
              'required' => ['color', 'type'],
              'additionalProperties' => false,
            ],
          ],
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
