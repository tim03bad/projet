<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $loggedIn = $this->security->isGranted('ROLE_USER');
        if ($loggedIn){
            $member = $this->security->getUser()->getMember();
            $builder
            ->add('email')
            ->add('password')
            ->add('member', MemberType::class, array('data'=>$member))
            ;
        }
        else {
            $builder
            ->add('email')
            ->add('password')
            ->add('member', MemberType::class)
            ->add('roles', CollectionType::class, array('data'=>['ROLE_USER']))
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
