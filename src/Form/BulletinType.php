<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Bulletin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BulletinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'General' => 'general',
                    'Divers' => 'divers',
                    'Urgent' => 'urgent',
                ],
                'expanded' => false, //* pour avoir des boutons mettre true
                'multiple' => false, //* pour pouvoir en choisir plsrs ou pas
            ])
            ->add('tags', EntityType::class, [ //* on récupère une entité
                'label' => 'Tags',
                'choice_label' => 'name', //* On accéde à l'attribut name de l'objet
                'class' => Tag::class, //* permet de préciser quelle entité on récupère
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu'
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'style' => 'margin-top : 5px',
                    'class' => 'btn btn-success',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bulletin::class,
        ]);
    }
}
