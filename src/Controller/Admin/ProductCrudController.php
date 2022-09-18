<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Request;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            //->add(Crud::PAGE_INDEX, Action::DETAIL)
            //->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }

    public function edit(AdminContext $context)
    {
        //dd($context->getRequest()->request->get('product'));

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($context->getRequest());

        return $this->render('admin/product/edit.html.twig', [
            'product' => [],
            'form' => $form->createView(),
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('brand'),
            AssociationField::new('category'),
            TextEditorField::new('description'),
            NumberField::new('price'),
            ImageField::new('image')->hideOnForm(),
            ImageField::new('imageFile')->onlyOnForms(),
            DateTimeField::new('created_at')->onlyOnIndex()->setValue(new \DateTime('now')),
            DateTimeField::new('updated_at')->onlyOnIndex()
        ];
    }

}
