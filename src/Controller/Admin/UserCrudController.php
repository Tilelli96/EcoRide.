<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setAutofocusSearch()
            ->setPaginatorPageSize(10)
            ->hideNullValues()
            ->setSearchFields(['email', 'nom', 'prenom', 'pseudo', 'roles'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $passwordField = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->onlyOnForms()
            ->setRequired(true)
            ->setLabel('Mot de passe');
        if ($pageName === Crud::PAGE_NEW) {
            $passwordField->setRequired(true);
        } else {
            $passwordField->setRequired(false)->onlyOnForms();
        }
        return [
            IdField::new('id')->onlyOnIndex(),
            EmailField::new('email')->setRequired(true),
            ChoiceField::new('roles')
                ->setLabel('Rôle')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Employé' => 'ROLE_EMPLOYE',
                    'Admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded()
                ->setRequired(true),
            $passwordField,
            TextField::new('nom')->setRequired(true)->onlyOnForms(),
            TextField::new('prenom')->setRequired(true)->onlyOnForms(),
            TextField::new('telephone')->setRequired(false),
            TextField::new('adresse')->setRequired(false)->onlyOnForms(),
            DateTimeField::new('date_naissance')->setRequired(true)->onlyOnForms(),
            TextField::new('pseudo')->setRequired(false),
            NumberField::new('note')->setRequired(false)->onlyOnForms(),
            IntegerField::new('credit')->setRequired(false)->onlyOnForms(),
            BooleanField::new('isVerified')->setLabel('Email vérifié ?')->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if (!$entityInstance instanceof User) return;

        if ($entityInstance->getPassword()) {
            $hashed = $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword());
            $entityInstance->setPassword($hashed);
        }

        $roles = $entityInstance->getRoles();
        if (in_array('ROLE_EMPLOYE', $roles)) {
            $entityInstance->setNote($entityInstance->getNote() ?? 0);
            $entityInstance->setCredit($entityInstance->getCredit() ?? 0);
            $entityInstance->setDateNaissance($entityInstance->getDateNaissance() ?? new \DateTime('2000-01-01'));
        }

        parent::persistEntity($em, $entityInstance);
    }

}
