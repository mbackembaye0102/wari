<?php

namespace App\Form;

use App\Entity\Expediteur;
use App\Entity\Transaction;
use App\Entity\Utilisateur;
use App\Entity\Beneficiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('montant')
            ->add('frais')
            ->add('total')
            ->add('commissionWari')
            ->add('CommissionPartenaire')
            ->add('commissionEtat')
            ->add('guichetier',EntityType::class,['class'=>Utilisateur::class])
            ->add('Expediteur',EntityType::class,['class'=>Expediteur::class])
            ->add('beneficiaire',EntityType::class,['class'=>Beneficiaire::class])
            ->add('dateEnvoie')
            ->add('dateRetrait')
            ->add('typeOp')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
