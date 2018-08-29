<?php

namespace AppBundle\Form;

use AppBundle\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array('label' => 'Nom Todo'))
                ->add('description', TextareaType::class, array('label' => 'Description'))
                ->add('dueDate', DateTimeType::class, array(
                    'label' => 'Date',
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Todo::class,
        ));
    }
}
