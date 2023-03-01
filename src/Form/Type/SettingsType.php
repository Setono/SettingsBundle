<?php

declare(strict_types=1);

namespace Setono\SettingsBundle\Form\Type;

use Setono\SettingsBundle\Config\SettingsRegistryInterface;
use Setono\SettingsBundle\Settings\SettingsInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<SettingsInterface>
 */
final class SettingsType extends AbstractType
{
    public function __construct(private readonly SettingsRegistryInterface $settingsRegistry)
    {
    }

    /**
     * @param array{data_class: class-string<SettingsInterface>} $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $settingsConfig = $this->settingsRegistry->get($options['data_class']);
        foreach ($settingsConfig->settings as $setting) {
            $builder->add($setting->name, $setting->formType, array_filter(array_merge($setting->formTypeOptions, [
                'label' => $setting->label,
                'help' => $setting->help,
            ])));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('label', false)->setRequired('data_class');
    }
}
