<?php

namespace App\Form;

use App\Entity\DevisProduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Produit;

class DevisProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'designation',
                'label' => 'Produit',
            ])
            ->add('nb_apprenant')
            ->add('montant_ht')
            ->add('taxe_tva')
            ->add('montant_remise')
            // ->add('devis')
            // ->add('product')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DevisProduit::class,
            'allow_extra_fields' => true,
        ]);
    }
}
