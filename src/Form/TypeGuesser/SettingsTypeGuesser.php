<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Form\TypeGuesser;

use Setono\SettingsBundle\Config\SettingsRegistryInterface;
use Setono\SettingsBundle\Settings\SettingsInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Component\Form\Guess\Guess;
use Symfony\Component\Form\Guess\TypeGuess;
use Symfony\Component\Form\Guess\ValueGuess;

final class SettingsTypeGuesser implements FormTypeGuesserInterface
{
    public function __construct(private readonly SettingsRegistryInterface $settingsRegistry)
    {
    }

    public function guessType(string $class, string $property): ?TypeGuess
    {
        if (!is_a($class, SettingsInterface::class, true)) {
            return null;
        }

        try {
            $setting = $this->settingsRegistry->get($class)->getSetting($property);
        } catch (\InvalidArgumentException) {
            return null;
        }

        if (!$setting->hasTypes()) {
            return null;
        }

        if ($setting->hasMultipleTypes()) {
            return new TypeGuess(TextType::class, [], Guess::LOW_CONFIDENCE);
        }

        return match ($setting->types[0]) {
            'int' => new TypeGuess(IntegerType::class, [], Guess::HIGH_CONFIDENCE),
            'float' => new TypeGuess(NumberType::class, [], Guess::HIGH_CONFIDENCE),
            'string' => new TypeGuess(TextType::class, [], Guess::HIGH_CONFIDENCE),
            'bool' => new TypeGuess(CheckboxType::class, [], Guess::HIGH_CONFIDENCE),
            default => new TypeGuess(TextType::class, [], Guess::LOW_CONFIDENCE),
        };
    }

    public function guessRequired(string $class, string $property): ?ValueGuess
    {
        if (!is_a($class, SettingsInterface::class, true)) {
            return null;
        }

        try {
            $setting = $this->settingsRegistry->get($class)->getSetting($property);
        } catch (\InvalidArgumentException) {
            return null;
        }

        return new ValueGuess(!$setting->nullable, Guess::HIGH_CONFIDENCE);
    }

    public function guessMaxLength(string $class, string $property): ?ValueGuess
    {
        return null;
    }

    public function guessPattern(string $class, string $property): ?ValueGuess
    {
        return null;
    }
}
