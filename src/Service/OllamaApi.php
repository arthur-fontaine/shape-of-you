<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaApi
{
  public function __construct(
    private string $apiUrl,
    private HttpClientInterface $client,
  ) {}

  /**
   * @param OllamaMessage[] $messages
   */
  public function chat(array $messages, string $model, array|null $format = null): string
  {
    $response = $this->client->request(
      "POST",
      $this->apiUrl . "chat",
      [
        "headers" => [
          "Content-Type" => "application/json",
        ],
        "json" => [
          "model" => $model,
          "messages" => array_map(
            fn (OllamaMessage $message) => $message->toArray(),
            $messages
          ),
          "stream" => false,
          "format" => $format,
        ],
      ]
    );

    return $response->toArray()["message"]["content"];
  }
}

class OllamaMessage
{
  public function __construct(
    private string $message,
    private OllamaRole $role,
    private array $additionalData = [],
  ) {}

  public function getMessage(): string
  {
    return $this->message;
  }

  public function getRole(): OllamaRole
  {
    return $this->role;
  }

  public function getAdditionalData(): array
  {
    return $this->additionalData;
  }

  public function toArray(): array
  {
    return array_merge(
      [
        "content" => $this->message,
        "role" => $this->role,
      ],
      $this->additionalData
    );
  }
}

enum OllamaRole: string
{
  case USER = "user";
  case ASSISTANT = "assistant";
  case SYSTEM = "system";
  case TOOL = "tool";
}
