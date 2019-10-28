<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ArticleFormType
 * @package App\Form
 */
class ArticleFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'purify_html' => true,
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Максимум {{ limit }} символов'
                    ]),
                    new NotBlank([
                        'message' => 'Укажите заголовок'
                    ])
                ],
                ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'required' => true,
                'purify_html' => true,
                'constraints' => [
                    new Length([
                        'max' => 300,
                        'maxMessage' => 'Максимум {{ limit }} символов'
                    ]),
                    new NotBlank([
                        'message' => 'Напишите текст'
                    ])
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Article',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'article';
    }
}
