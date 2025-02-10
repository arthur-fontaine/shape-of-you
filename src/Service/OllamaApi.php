<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OllamaApi
{
  public function __construct(
    private string $model,
    private string $apiUrl,
    private HttpClientInterface $client,
  ) {}

  /**
   * @param Message[] $messages
   */
  public function chat(array $messages, array $format): string
  {
    $response = $this->client->request(
      "POST",
      $this->apiUrl . "chat",
      [
        "headers" => [],
        "body" => [
          "model" => $this->model,
          "messages" => array_map(
            fn (Message $message) => $message->toArray(),
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

class Message
{
  public function __construct(
    private string $message,
    private Role $role,
    private array $additionalData = [],
  ) {}

  public function getMessage(): string
  {
    return $this->message;
  }

  public function getRole(): Role
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

enum Role
{
  case USER;
  case BOT;
}
