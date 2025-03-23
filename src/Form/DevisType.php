<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Client;
use App\Entity\soc\Societe;
use App\Repository\SocieteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DevisType extends AbstractType
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
            ->add('ref_devis')
            ->add('date_devis', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'type' => 'date'],
                'label' => 'Date du devis',
                'label_attr' => ['class' => 'mb-1 text-sm font-semibold font-nunito text-blue-color'],
            ])
            ->add('date_echeance', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50', 'type' => 'date'],
                'label' => 'Date d\'échéance',
                'label_attr' => ['class' => 'mb-1 text-sm font-semibold font-nunito text-blue-color'],
            ])
            ->add('titre_devis')
            ->add('content')
            ->add('total_ht')
            ->add('tva')
            ->add('total_ttc')
            ->add('total_remise')
            ->add('society', EntityType::class, [
                'class' => Societe::class,
                'choices' => $userSocietes,
                'choice_label' => 'raisonSociale',
                'label' => 'Société',
                'placeholder' => 'Séléctioner une société',
            ])
            ->add('devisProduits', CollectionType::class, [
                'entry_type' => DevisProduitType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $devis = $event->getData();
                $society = $devis && $devis->getSociety() ? $devis->getSociety() : null;
                $this->setupClientField($form, $society);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $societyId = $data['society'] ?? null;
                $form = $event->getForm();
                $this->setupClientField($form, $societyId);
            });
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
            'data_class' => Devis::class,
            'user_societes' => [],
        ]);
    }
}
