<?php

namespace App\Controller\Admin;

use App\Entity\Videotheque;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class VideothequeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Videotheque::class;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('description'),
            AssociationField::new('member'),
            AssociationField::new('contenu')->onlyOnDetail()->setTemplatePath('admin/fields/videotheque_video.html.twig')
            
        ];
    }
    
}
