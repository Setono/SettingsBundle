<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Setting
{
    public ?string $label = null;

    public ?string $help = null;

    public ?string $formType = null;

    /** @var array<string, mixed> */
    public array $formTypeOptions = [];
}
