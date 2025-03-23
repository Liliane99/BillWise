<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\soc\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('designation')
            ->add('nb_apprenant_min')
            ->add('nb_apprenant_max')
            ->add('price_unit', null, [
                'label' => 'Prix unitaire',
                'attr' => ['class' => 'custom-class', 'placeholder' => 'Prix en euros']
            ])
            
            ->add('categorie', ChoiceType::class, [
                'choices' => Produit::CATEGORIES,
                'expanded' => false, 
                'multiple' => false, 
                'label' => 'Catégorie',
                'placeholder' => 'Séléctionner une catégorie',
            ])
            ->add('taux_tva')
            ->add('duration')
            ->add('exigeance')
            ->add('certification')
            ->add('society', EntityType::class, [ 
                'class' => Societe::class,
                'choices' => $user->getSocieteId()->toArray(), 
                'choice_label' => 'raisonSociale',
                'multiple' => false,
                'expanded' => false,
                'label' => 'Société',
                'placeholder' => 'Séléctionner une société',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'user' => null, 
        ]);
    }
}
