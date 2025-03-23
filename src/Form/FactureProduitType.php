<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\FactureProduit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;



class FactureProduitType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; 
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

        $builder
            ->add('product', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'designation',  // Remplacez par le champ correct pour l'affichage dans la liste
                'placeholder' => 'Sélectionner un produit',
            ])
            ->add('nb_apprenant')
            ->add('montant_ht')
            ->add('taxe_tva')
            ->add('montant_remise')

            // Ajoutez un événement pour mettre à jour le prix unitaire en fonction du produit choisi
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                // Vérifiez si le produit est sélectionné
                if (isset($data['produit'])) {
                    // Récupérez le produit à partir de la base de données
                    $produit = $this->entityManager->getRepository(Produit::class)->find($data['produit']);

                    // Mettez à jour le prix unitaire dans le formulaire
                    $form->get('priceUnit')->setData($produit->getPriceUnit());
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FactureProduit::class,
            'allow_extra_fields' => true,
        ]);
    }
}