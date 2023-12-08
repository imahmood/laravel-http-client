<?php
declare(strict_types=1);

namespace Imahmood\HttpClient\Enums;

enum ContentType: string
{
    case JSON = 'json';
    case FORM = 'form';
    case MULTIPART = 'multipart';
}
