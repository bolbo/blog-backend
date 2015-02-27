<?php

namespace Bolbo\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'description' => 'post title',
        ));
        $builder->add('content', 'textarea', array(
            'description' => 'post content',
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                                   'data_class' => 'Bolbo\Component\Model\Database\PublicSchema\Post',
                                   'intention' => 'post',
                                   'translation_domain' => 'BolboBlogBundle'
                               ));
    }

    public function getName()
    {
        return 'post';
    }
}
