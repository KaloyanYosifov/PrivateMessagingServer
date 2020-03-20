<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static AttachmentType IMAGE()
 * @method static AttachmentType AUDIO()
 */
class AttachmentType extends Enum
{
    private const IMAGE = 'image';
    private const AUDIO = 'audio';
}
