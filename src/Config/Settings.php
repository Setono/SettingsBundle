<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Config;

use Setono\SettingsBundle\Settings\SettingsInterface;
use Webmozart\Assert\Assert;

/**
 * This class represents a settings class defined in the configuration of this bundle
 */
final class Settings
{
    /** @var class-string<SettingsInterface> */
    public string $class;

    /**
     * An array of defined settings, indexed by name
     *
     * @var array<string, Setting>
     */
    public array $settings = [];

    /**
     * @param class-string<SettingsInterface> $class
     */
    public function __construct(string $class)
    {
        if (!is_a($class, SettingsInterface::class, true)) {
            throw new \RuntimeException(sprintf('The class %s does not implement the %s', $class, SettingsInterface::class));
        }

        $this->class = $class;

        $refl = new \ReflectionClass($class);
        foreach ($refl->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $setting = Setting::fromReflection($property);
            $this->settings[$setting->name] = $setting;
        }
    }

    /**
     * @param array{class: class-string<SettingsInterface>} $settings
     */
    public static function fromArray(array $settings): self
    {
        return new self($settings['class']);
    }

    public function hasSetting(string $setting): bool
    {
        return isset($this->settings[$setting]);
    }

    /**
     * @throws \InvalidArgumentException if the setting doesn't exist
     */
    public function getSetting(string $setting): Setting
    {
        Assert::true($this->hasSetting($setting));

        return $this->settings[$setting];
    }
}
