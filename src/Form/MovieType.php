<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            "label" => "Titre"
        ]);

        $builder->add('imageFile', FileType::class, [
            "label" => "Affiche",
            "mapped" => false,
            "required" => false,
            'constraints' => [
            new Image([
                'maxSize' => '1024k',
                ])
            ]
        ]);

        $builder->add('releaseDate', DateType::class, [
            "label" => "Date de sortie",
            'widget' => "single_text"
        ]);

        $builder->add('categories', EntityType::class, [
            "label" => "Catégories",
            'class' => Category::class,
            'choice_label' => 'label',
            "multiple" => true,
            "expanded" => true
        ]);

        $builder->add('director', EntityType::class, [
            "label" => "Réalisateur",
            'class' => Person::class,
            'choice_label' => 'name'
        ]);

        $builder->add('writers', EntityType::class, [
            "label" => "Scénaristes",
            'class' => Person::class,
            'choice_label' => 'name',
            "multiple" => true
        ]);

        $builder->add('movieActors', CollectionType::class, [
            'entry_type' => MovieActorType::class,
            'label' => false,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            "by_reference" => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
