<?php

namespace Bolbo\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',
            'text',
            [
                'description' => 'post title',
            ]
        );
        $builder->add('content',
            'textarea',
            [
                'description' => 'post content',
            ]
        );

        $builder->add('meta_title',
            'textarea',
            [
                'description' => 'Meta Title',
            ]
        );
        $builder->add('meta_description',
            'textarea',
            [
                'description' => 'Meta Description',
            ]
        );
        $builder->add('meta_keyword',
            'textarea',
            [
                'description' => 'Meta Keyword',
            ]
        );

        $builder->add('tag',
            'textarea',
            [
                'description' => 'Tag list',
            ]
        );
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'Bolbo\Component\Model\Database\PublicSchema\Post',
            'intention'          => 'post',
            'translation_domain' => 'BolboBlogBundle'
        ));
    }


    public function getName()
    {
        return 'post';
    }
}
