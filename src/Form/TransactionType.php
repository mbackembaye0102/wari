<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Entity\Utilisateur;
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
            ->add('dateEnvoie')
            ->add('dateRetrait')
            ->add('commissionRetrait')
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('numeroPiece')
            ->add('typePiece')
            ->add('etat')
            ->add('prenomb')
            ->add('nomb')
            ->add('telephoneb')
            ->add('numeroPieceb')
            ->add('typePieceb')
            ->add('guichetier',EntityType::class,['class'=>Utilisateur::class])
            ->add('guichetierRetrait',EntityType::class,['class'=>Utilisateur::class])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
