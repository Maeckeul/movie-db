<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            "label" => "Titre"
        ]);
        $builder->add('content', TextType::class, [
            "label" => "Article",
        ]);
        $builder->add('createdAt', DateType::class, [
            "label" => "Crée le",
            'widget' => "single_text"
        ]);
        $builder->add('movies', EntityType::class, [
            'label' => "Film",
            'class' => Movie::class,
            'choice_label' => 'title',
            "multiple" => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'required' => false
        ]);
    }
}
