<?php

namespace App\Enum;

enum Gender: string
{
  case MALE = "male";
  case FEMALE = "female";
  case NON_BINARY = "non_binary";
  case NOT_SPECIFIED = "not_specified";
}
