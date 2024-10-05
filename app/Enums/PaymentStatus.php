<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case PENDING = -1;
    case INTERNAL_ERROR = -2;
    case SUCCESS_CONFIRMED = 1;
    case SUCCESS_UNCONFIRMED = 2;
    case CANCELED_BY_USER = 3;
    case INVALID_CARD = 4;
    case INSUFFICIENT_BALANCE = 5;
    case WRONG_PASSWORD = 6;
    case REQUEST_LIMIT_EXCEEDED = 7;
    case DAILY_TRANSACTION_LIMIT_EXCEEDED = 8;
    case DAILY_AMOUNT_LIMIT_EXCEEDED = 9;
    case INVALID_CARD_ISSUER = 10;
    case SWITCH_ERROR = 11;
    case CARD_NOT_ACCESSIBLE = 12;
}
