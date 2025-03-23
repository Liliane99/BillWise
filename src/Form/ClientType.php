<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\soc\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('num_tel')
            ->add('num_fix')
            ->add('email')
            ->add('adresse')
            ->add('code_postal')
            ->add('ville')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Particulier' => 'Particulier',
                    'Entreprise' => 'Entreprise',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('raison_sociale')
            ->add('num_siret')
               
            ->add('society', EntityType::class, [ 
                'class' => Societe::class,
                'choices' => $user->getSocieteId()->toArray(), 
                'choice_label' => 'raisonSociale',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Société',
                'placeholder' => 'Choisissez une societe',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'user' => null,
        ]);
    }
}


