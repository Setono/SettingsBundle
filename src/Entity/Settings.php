<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Entity;

use Setono\SettingsBundle\Settings\SettingsInterface;
use Webmozart\Assert\Assert;

class Settings
{
    protected ?int $id = null;

    /** @var class-string<SettingsInterface>|null */
    protected ?string $class = null;

    protected null|string|SettingsInterface $values = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return class-string<SettingsInterface>|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    public function getValues(): ?SettingsInterface
    {
        Assert::nullOrIsInstanceOf($this->values, SettingsInterface::class);

        return $this->values;
    }

    public function setValues(SettingsInterface $values): self
    {
        $this->class = $values::class;
        $this->values = $values;

        return $this;
    }
}
