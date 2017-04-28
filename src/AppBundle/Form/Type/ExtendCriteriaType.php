<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 28.04.17
 * Time: 1:44
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Criteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtendCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('save', SubmitType::class, array(
                'attr' => array('class' => 'save'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Criteria::class,
        ));
    }
}