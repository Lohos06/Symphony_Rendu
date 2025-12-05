<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('auteur', EntityType::class,[
                    'class' => Auteur::class,// affiche la liste des auteurs venant de la base de données
                    'choice_label' => 'nom', // ce qui sera affiché dans la liste :  nom auteur
                    'mapped' => false,
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'nom',  
                'label' => 'Genre',
                 'mapped' => false,
            ])
            ->add('titre', TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class)
            
        ;
    }
}
