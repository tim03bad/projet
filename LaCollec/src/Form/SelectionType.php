<?php

namespace App\Form;

use App\Entity\Selection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

class SelectionType extends AbstractType
{
    private $security;
    
    public function __construct(Security $security, ManagerRegistry $doctrine)
    {
        $this->security = $security;
        $this->doctrine = $doctrine;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $member = $this->security->getUser()->getMember();
        $builder
        ->add('description')
            ->add('publie')
            ->add('video')
            ->add('member', MemberType::class, array('data'=>$member))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Selection::class,
        ]);
    }
}
