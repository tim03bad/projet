<?php

namespace App\Controller\Admin;

use App\Entity\Selection;
use Doctrine\DBAL\Query\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SelectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Selection::class;
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
            AssociationField::new('member'),
            BooleanField::new('publie')
                ->onlyOnForms()
                ->hideWhenCreating(),
            TextField::new('description'),
            
            AssociationField::new('video')
            ->onlyOnForms()
            // on ne souhaite pas gérer l'association entre les
            // [objets] et la [galerie] dès la crétion de la
            // [galerie]
            ->hideWhenCreating()
            ->setTemplatePath('admin/fields/videotheque_video.html.twig')
            // Ajout possible seulement pour des [objets] qui
            // appartiennent même propriétaire de l'[inventaire]
            // que le [createur] de la [galerie]

        ];
    }
}
