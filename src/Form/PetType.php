<?php

namespace App\Form;

use App\Entity\Pet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    "class" => "w-full rounded-lg p-6 border-2 border-slate-200 hover:border-slate-600",
                    "placeholder" => "Nombre de la mascota (si lo conoce)"
                ],
                "label" => false,
                "required" => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    "class" => "w-full rounded-lg p-6 mt-4 border-2 border-slate-200 hover:border-slate-600",
                    "placeholder" => "Describa a la mascota"
                ],
                "label" => false
            ])
            ->add('species', ChoiceType::class, [
                'choices' => [
                    'Gato' => 'cat',
                    'Perro' => 'dog',
                ],
                'attr' => [
                    "class" => "w-full rounded-lg p-6 mt-4 border-2 border-slate-200 hover:border-slate-600",
                ],
                "label" => false
            ])
            ->add('location', TextType::class, [
                'attr' => [
                    "class" => "w-full rounded-lg p-6 mt-4 border-2 border-slate-200 hover:border-slate-600",
                    "placeholder" => "¿Donde se encuentra la mascota?"
                ],
                "label" => false
            ])
            ->add('photo', FileType::class, [
                'attr' => [
                    "class" => "py-4 px-6 mt-4",
                ],
                'label' => 'Subir una imagen'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    "class" => "py-4 px-6 mt-4 rounded-xl bg-slate-400 text-white",
                ],
                'label' => 'Enviar'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}
