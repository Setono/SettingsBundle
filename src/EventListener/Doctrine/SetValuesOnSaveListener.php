<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\EventListener\Doctrine;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Setono\SettingsBundle\Entity\Settings;
use Symfony\Component\Serializer\SerializerInterface;

final class SetValuesOnSaveListener
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function prePersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->setSerializedValues($lifecycleEventArgs);
    }

    public function preUpdate(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->setSerializedValues($lifecycleEventArgs);
    }

    private function setSerializedValues(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $obj = $lifecycleEventArgs->getObject();
        if (!$obj instanceof Settings) {
            return;
        }

        $json = $this->serializer->serialize($obj->getValues(), 'json');

        $reflectionProperty = new \ReflectionProperty($obj, 'values');
        $reflectionProperty->setValue($obj, $json);
    }
}
