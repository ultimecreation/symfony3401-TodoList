<?php

namespace AppBundle\Form;

use AppBundle\Entity\AppUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AppUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array('label' => 'Nom Utilisateur'))
                ->add('email', EmailType::class, array('label' => 'Email'))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de Passe'),
                    'second_options' => array('label' => 'Répéter le Mot de Passe'),
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AppUser::class,
        ));
    }
}
