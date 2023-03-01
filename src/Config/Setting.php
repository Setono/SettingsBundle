<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Config;

use Setono\SettingsBundle\Attribute\Setting as SettingAttribute;
use Webmozart\Assert\Assert;

final class Setting
{
    public string $name;

    /**
     * A list of all non-null types this setting has
     *
     * @var list<string>
     */
    public array $types = [];

    public bool $nullable;

    public ?string $formType = null;

    public ?string $label = null;

    /**
     * @param list<string> $types
     */
    public function __construct(string $name, array $types)
    {
        $this->name = $name;
        $this->nullable = in_array('null', $types, true);
        $types = array_values(array_filter($types, static function (string $type): bool {
            return 'null' !== $type;
        }));
        Assert::minCount($types, 1);

        $this->types = $types;
    }

    public static function fromReflection(\ReflectionProperty $reflectionProperty): self
    {
        /** @var \ReflectionNamedType|\ReflectionUnionType|\ReflectionIntersectionType $type */
        $type = $reflectionProperty->getType();
        $types = match (true) {
            $type instanceof \ReflectionNamedType => [$type->getName()],
            $type instanceof \ReflectionUnionType, $type instanceof \ReflectionIntersectionType => array_map(static function (\ReflectionType $reflectionType) {
                return (string) $reflectionType;
            }, $type->getTypes()),
            default => [],
        };

        $obj = new self($reflectionProperty->getName(), $types);

        foreach ($reflectionProperty->getAttributes(SettingAttribute::class) as $attribute) {
            /** @var SettingAttribute $settingAttribute */
            $settingAttribute = $attribute->newInstance();
            $obj->formType = $settingAttribute->formType;
            $obj->label = $settingAttribute->label;
        }

        return $obj;
    }

    public function hasTypes(): bool
    {
        return [] !== $this->types;
    }

    /**
     * Returns true if the setting has two or more types
     */
    public function hasMultipleTypes(): bool
    {
        return count($this->types) > 1;
    }
}
