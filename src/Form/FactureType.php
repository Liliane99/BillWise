<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Produit;
use App\Entity\soc\Societe;
use App\Repository\SocieteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Entity\Client;



class FactureType extends AbstractType
{
    private $societeRepository;

    public function __construct(SocieteRepository $societeRepository)
    {
        $this->societeRepository = $societeRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userSocietes = $options['user_societes'];

        $builder
            ->add('client', EntityType::class, [
                'class' => 'App\Entity\Client', 
                'choice_label' => function ($client) {
                    return $client->getNom() . ' ' . $client->getPrenom();
                },
            ])
            ->add('society', EntityType::class, [
                'class' => 'App\Entity\soc\Societe', 
                'choices' => $userSocietes,
                'choice_label' => 'raisonSociale',
                'label' => 'Société',
                'placeholder' => 'Choisir une société',
            ])
            ->add('ref_facture')
            ->add('titre_facture')
            ->add('date_facture' , DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'type' => 'date'],
                'label' => 'Date du devis',
                'label_attr' => ['class' => 'mb-1 text-sm font-semibold font-nunito text-blue-color'],
            ])
            ->add('condition', ChoiceType::class, [
                'choices' => [
                    'En 1 fois' => 'En 1 fois',
                    'En 3 fois' => 'En 3 fois',
                    'En 4 fois' => 'En 4 fois',
                ],
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Choisir une condition de paiement',
            ])
            ->add('date_echeance', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'type' => 'date'],
                'label' => 'Date d\'échéance',
                'label_attr' => ['class' => 'mb-1 text-sm font-semibold font-nunito text-blue-color'],
            ])
            ->add('factureProduits', CollectionType::class, [
                'entry_type' => FactureProduitType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $facture = $event->getData();
                $society = $facture && $facture->getSociety() ? $facture->getSociety() : null;
                $this->setupClientField($form, $society);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $societyId = $data['society'] ?? null;
                $form = $event->getForm();
                $this->setupClientField($form, $societyId);
            })
            ->add('total_ht')
            ->add('condition_termes')
            ->add('tva' )
            ->add('total_ttc' )
            ->add('total_remise')
            
            
        ;
    }

    private function setupClientField($form, $society)
    {
        if (is_string($society)) {
            $society = $this->societeRepository->find($society);
        }
        
        $form->add('client', EntityType::class, [
            'class' => Client::class,
            'choices' => $society ? $society->getClients() : [],
            'choice_label' => function (Client $client) {
                return $client->getType() === 'particulier' ? $client->getNom() . ' ' . $client->getPrenom() : $client->getRaisonSociale();
            },
            'placeholder' => 'Sélectionnez un client',
            'auto_initialize' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'user_societes' => [],
        ]);
    }
}