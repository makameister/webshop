<?php

namespace App\Form;

use App\Entity\Search\ProductSearch;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minPrice', RangeType::class, [
                'required' => false,
                'label' => 'Prix minimun',
                'attr' => [
                    'min' => 0,
                    'max' => 3000,
                    'class' => "custom-range"
                ]
            ])
            ->add('maxPrice', RangeType::class, [
                'required' => false,
                'label' => 'Prix maximun',
                'attr' => [
                    'min' => 0,
                    'max' => 3000,
                    "class" => "custom-range"
                ]
            ])
            ->add('brand', ChoiceType::class, [
                'choices' => $this->productRepository->findByCategoryAll($options['data']->getCategory()),
                'placeholder' => 'choisir une marque...',
                'choice_label' => 'brand',
                'required' => false,
                'label' => 'Marque'
            ])
            ->add('order', ChoiceType::class, [
                'required' => false,
                'label' => 'Affichage',
                'placeholder' => 'choisir l\'ordre d\'affichage...',
                'choices' => [
                    "Du plus récent au moins récent" => 0,
                    "Du moins récent au plus récent" => 1,
                    "Du moins chère au plus chère" => 2,
                    "Du plus chère au moins chère" => 3
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductSearch::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
