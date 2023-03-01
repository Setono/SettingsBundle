<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Setting
{
    public function __construct(
        public readonly ?string $label = null,
        public readonly ?string $help = null,
        public readonly ?string $formType = null,
        /**
         * @var array<string, mixed> $formTypeOptions
         */
        public readonly array $formTypeOptions = [],
    ) {
    }
}
