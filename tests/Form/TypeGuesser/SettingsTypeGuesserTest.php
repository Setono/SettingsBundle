<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Tests\Form\TypeGuesser;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Setono\SettingsBundle\Config\Settings;
use Setono\SettingsBundle\Config\SettingsRegistryInterface;
use Setono\SettingsBundle\Form\TypeGuesser\SettingsTypeGuesser;
use Setono\SettingsBundle\Settings\SettingsInterface;

/**
 * @covers \Setono\SettingsBundle\Form\TypeGuesser\SettingsTypeGuesser
 */
final class SettingsTypeGuesserTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function it_returns_null_if_the_class_not_an_instance_of_settings_interface(): void
    {
        $guesser = new SettingsTypeGuesser($this->prophesize(SettingsRegistryInterface::class)->reveal());
        self::assertNull($guesser->guessType(ClassNotImplementingSettingsInterface::class, 'prop'));
    }

    /**
     * @test
     */
    public function it_returns_null_if_the_class_is_not_registered_as_settings_class(): void
    {
        $settingsRegistry = $this->prophesize(SettingsRegistryInterface::class);
        $settingsRegistry->get(ClassImplementingSettingsInterface::class)->shouldBeCalledOnce()->willThrow(\InvalidArgumentException::class);

        $guesser = new SettingsTypeGuesser($settingsRegistry->reveal());

        self::assertNull($guesser->guessType(ClassImplementingSettingsInterface::class, 'prop'));
    }

    /**
     * @test
     */
    public function it_returns_null_if_the_class_is_does_not_have_the_asked_property(): void
    {
        $settings = new Settings(ClassImplementingSettingsInterface::class);

        $settingsRegistry = $this->prophesize(SettingsRegistryInterface::class);
        $settingsRegistry->get(ClassImplementingSettingsInterface::class)->willReturn($settings);

        $guesser = new SettingsTypeGuesser($settingsRegistry->reveal());

        self::assertNull($guesser->guessType(ClassImplementingSettingsInterface::class, 'prop'));
    }
}

final class ClassNotImplementingSettingsInterface
{
}

final class ClassImplementingSettingsInterface implements SettingsInterface
{
    public string $setting = 'value';
}
