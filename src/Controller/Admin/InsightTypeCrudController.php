<?php

namespace App\Controller\Admin;

use App\Entity\InsightType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InsightTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InsightType::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
