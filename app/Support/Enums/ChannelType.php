<?php

namespace App\Support\Enums;

enum ChannelType: string
{
    case Slack = 'slack';
    case Email = 'email';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';
}
