<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\EventListener\Doctrine;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Setono\SettingsBundle\Entity\Settings;
use Setono\SettingsBundle\Settings\SettingsInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class SetValuesOnLoadListener
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function postLoad(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $obj = $lifecycleEventArgs->getObject();
        if (!$obj instanceof Settings) {
            return;
        }

        $class = $obj->getClass();
        if (null === $class) {
            return;
        }

        $serializedValues = (new \ReflectionProperty($obj, 'values'))->getValue($obj);
        if (!is_string($serializedValues)) {
            return;
        }

        $settings = $this->serializer->deserialize($serializedValues, $class, 'json');
        Assert::isInstanceOf($settings, SettingsInterface::class);

        $obj->setValues($settings);
    }
}
