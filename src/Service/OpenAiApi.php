<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAiApi
{
  public function __construct(
    private string $apiUrl,
    private string|null $apiKey,
    private HttpClientInterface $client,
  ) {}

  /**
   * @param OpenAiMessage[] $messages
   */
  public function chat(array $messages, string $model, array|null $format = null): string
  {
    $response = $this->client->request(
      "POST",
      $this->apiUrl . "v1/chat/completions",
      [
        "headers" => [
          "Content-Type" => "application/json",
          "Authorization" => "Bearer $this->apiKey",
        ],
        "json" => [
          "model" => $model,
          "messages" => array_map(
            fn(OpenAiMessage $message) => $message->toArray(),
            $messages
          ),
          "stream" => false,
          "response_format" => $format,
        ],
      ]
    );

    return $response->toArray()["choices"][0]["message"]["content"];
  }
}

class OpenAiMessage
{
  public function __construct(
    private string $message,
    private OpenAiRole $role,
    private array $additionalData = [],
  ) {}

  public function getMessage(): string
  {
    return $this->message;
  }

  public function getRole(): OpenAiRole
  {
    return $this->role;
  }

  public function getAdditionalData(): array
  {
    return $this->additionalData;
  }

  public function toArray(): array
  {
    return [
      "content" => [[
        "type" => "text",
        "text" => $this->message,
      ], ...$this->additionalData],
      "role" => $this->role,
    ];
  }
}

enum OpenAiRole: string
{
  case USER = "user";
  case ASSISTANT = "assistant";
  case SYSTEM = "system";
  case TOOL = "tool";
}
