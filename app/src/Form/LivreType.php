<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Repository\AuteurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'required' => true,
                'help' => "Veuillez donner un titre !!",
            ])
            ->add('dateParution', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'form-control',
                    'label' => 'Date de parution',
                ]
            ])
            ->add('resume')
            ->add('editeur')
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'query_builder' => function (AuteurRepository $ar) {
                    return $ar->orderByName('ASC');
                },
                'choice_label' => function ($auteur) {
                    // return $auteur->getNom(). '' .$auteur->getPrenom();
                    return $auteur->getFullName();
                },
                'placeholder' => 'Choisir un auteur',
                'required' => true,
                'multiple' => true,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => "label",
                'placeholder' => "Choisir une catégorie",
                'required' => true,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('enregistrement', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}