<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Config;

interface SettingsRegistryInterface
{
    /**
     * @param class-string $settings
     */
    public function has(string $settings): bool;

    /**
     * @param class-string $settings
     *
     * @throws \InvalidArgumentException if the settings doesn't exist
     */
    public function get(string $settings): Settings;
}
