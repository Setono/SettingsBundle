<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Config;

use Setono\SettingsBundle\Settings\SettingsInterface;
use Webmozart\Assert\Assert;

final class SettingsRegistry implements SettingsRegistryInterface
{
    /**
     * @var array<string, Settings>
     *
     * An array of settings indexed by the class name
     */
    private array $settings = [];

    /**
     * @param list<array{class: class-string<SettingsInterface>}> $settings
     */
    public static function fromArray(array $settings): self
    {
        $registry = new self();
        foreach ($settings as $setting) {
            $registry->add(Settings::fromArray($setting));
        }

        return $registry;
    }

    public function add(Settings $settings): void
    {
        $this->settings[$settings->class] = $settings;
    }

    public function has(string $settings): bool
    {
        return isset($this->settings[$settings]);
    }

    public function get(string $settings): Settings
    {
        Assert::true($this->has($settings));

        return $this->settings[$settings];
    }
}
