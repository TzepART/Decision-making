<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 28.04.17
 * Time: 1:44
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Variant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Variant::class,
        ));
    }
}