<?php

namespace App\Form;

use App\Entity\MovieActor;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('characterName', TextType::class, [
            "label" => "Rôle"
        ]);
        $builder->add('person', EntityType::class, [
            "label" => "Acteur",
            "class" => Person::class, 
            "choice_label" => 'name'        
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovieActor::class,
        ]);
    }
}
