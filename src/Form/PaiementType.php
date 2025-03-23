<?php

namespace App\Form;

use App\Entity\Paiement;
use App\Entity\Facture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_paiement', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'type' => 'date'],
                'label' => 'Date d\'échéance',
                'label_attr' => ['class' => 'mb-1 text-sm font-semibold font-nunito text-blue-color'],
            ])
            ->add('montant', NumberType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Montant',
                'scale' => 2, 
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Virement' => 'Virement',
                    'Espèces' => 'Espèces',
                    'Chèque' => 'Chèque',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Payé' => 'Payé',
                    'A venir' => 'A venir',
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add('facture', EntityType::class, [ 
                'class' => Facture::class, 
                'choice_label' => 'refFacture',
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Choisissez une facture', 
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}