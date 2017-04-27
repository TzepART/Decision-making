<?php

namespace AppBundle\Form;

use AppBundle\Entity\Task;
use AppBundle\Form\Type\CriteriaType;
use AppBundle\Form\Type\VariantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('variants', CollectionType::class, array(
                    'entry_type' => VariantType::class
                ))
                ->add('criteria', CollectionType::class, array(
                    'entry_type' => CriteriaType::class
                ))
                ->add('save', SubmitType::class, array(
                    'attr' => array('class' => 'save'),
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_task_form_type';
    }
}
