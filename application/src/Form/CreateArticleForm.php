<?php declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'Article title',
                'required' => true,
            ]
        );

        $builder->add(
            'subTitle',
            TextType::class,
            [
                'label' => 'Article sub-title',
                'required' => true,
            ]
        );

        $builder->add(
            'body',
            TextareaType::class,
            [
                'label' => 'Article text',
                'required' => true,
            ]
        );

        $builder->add('publishedDate', DateType::class, [
            'widget' => 'choice',
        ]);

        $builder->add('confirm', SubmitType::class, ['label' => 'Create Article']);
    }
}
