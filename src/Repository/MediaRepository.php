<?php

namespace App\Repository;

use Symfony\Component\HttpFoundation\File\File;

class MediaRepository
{
  private string $path;

  public function __construct(string $path)
  {
    $this->path = $path;
  }

  public function uploadBase64(string $base64): string
  {
    $data = explode(',', $base64);
    $extension = explode(';', explode('/', $data[0])[1])[0];
    $filename = uniqid() . '.' . $extension;
    $path = $this->path . '/' . $filename;
    file_put_contents($path, base64_decode($data[1]));

    return $filename;
  }

  public function upload(File $file): string
  {
    $filename = uniqid() . '.' . $file->guessExtension();
    $file->move($this->path, $filename);

    return $filename;
  }

  public function download(string $filename): string
  {
    return file_get_contents($this->path . '/' . $filename);
  }

  public function exists(string $filename): bool
  {
    return file_exists($this->path . '/' . $filename);
  }
}
