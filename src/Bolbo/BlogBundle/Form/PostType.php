<?php

namespace Bolbo\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PostType
 *
 * @package Bolbo\BlogBundle\Form
 */
class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            'text',
            [
                'required'    => true,
                'description' => 'post title',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 200]),
                ],
            ]
        );
        $builder->add('content',
            'textarea',
            [
                'required'    => true,
                'description' => 'post content',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5]),
                ],
            ]
        );

        $builder->add('meta_title',
            'textarea',
            [
                'required'    => false,
                'description' => 'Meta Title',
            ]
        );
        $builder->add('meta_description',
            'textarea',
            [
                'required'    => false,
                'description' => 'Meta Description',
            ]
        );
        $builder->add('meta_keyword',
            'textarea',
            [
                'required'    => false,
                'description' => 'Meta Keyword',
            ]
        );

        $builder->add('tag',
            'textarea',
            [
                'required'    => false,
                'description' => 'Tag list',
            ]
        );
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => 'Bolbo\Component\Model\Database\PublicSchema\Post',
                'intention'          => 'post',
                'translation_domain' => 'BolboBlogBundle'
            ]
        );
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'post';
    }
}
