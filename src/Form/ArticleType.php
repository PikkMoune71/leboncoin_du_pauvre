<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Tags;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
//            ->add('slug')
            ->add('image')
            ->add('subtitle')
            ->add('description', TextareaType::class, [
                'attr' => [
                    'style' => 'height: 200px'
                ]
            ])
            ->add('price', MoneyType::class, [
                'row_attr' => [
                    'class' => 'col-md-2'
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'row_attr' => [
                    'class' => 'col-md-3'
                ]
            ])
            ->add('tags', EntityType::class,[
                'class' => Tags::class,
                'row_attr' => [
                    'class' => 'col-md-3 mb-2'
                ]
            ])
//            ->add('users')
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
