<?php

declare(strict_types=1);

namespace App\Enum;

enum Transport: string
{
    case SMS   = 'sms';
    case EMAIL = 'email';
}
