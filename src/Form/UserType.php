<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\soc\Societe; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $adminSocietes = $options['admin_societes'];
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => User::ROLES,
                'expanded' => false, 
                'multiple' => true, 
                'label' => 'Roles'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'mapped' => false, 
                'required' => false, 
                'attr' => ['autocomplete' => 'new-password'], 
            ])
            ->add('name')
            ->add('surname')
            // ->add('created_at')
            // ->add('updated_at')
            ->add('profilePictureFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                // 'delete_label' => 'Supprimer',
                // 'download_label' => 'Télécharger',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('email')   
            ->add('societe_id', EntityType::class, [
                'class' => Societe::class,
                'choices' => $adminSocietes, 
                'choice_label' => 'raison_sociale',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Sociétés',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'admin' => null,
            'include_password' => true,
            'admin_societes' => [],
        ]);
    }
}