<?php

namespace App\Controller\Admin;

use App\Entity\Source;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Source::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('url'),
            TextField::new('selector'),
            TextareaField::new('script'),
            TextField::new('regex'),
            IntegerField::new('regexCaptureGroup'),
            AssociationField::new('journal')
                ->autocomplete()
                ->setLabel('Journal'),
            AssociationField::new('insightType')
                ->autocomplete()
                ->setLabel('Insight'),
        ];

        if($pageName === 'index') {
            $fields = array_merge([IntegerField::new('id')], $fields);
        }

        return $fields;
    }

}
