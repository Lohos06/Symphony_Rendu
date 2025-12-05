<?php

namespace App\Form;

use App\Entity\Auteur;  
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //form
        $builder
            ->add('nom', TextType::class)
            ->add('siecle', TextType::class)
            ->add('style', TextType::class)
            ->add('save', SubmitType::class) 
        ;
    }
}