<?php

namespace App\Form;

use App\Entity\Videotheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class VideothequeType extends AbstractType
{
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $member = $this->security->getUser()->getMember();
        
        $builder
            ->add('description')
            ->add('contenu')
            ->add('member', MemberType::class, array('data'=>$member))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Videotheque::class,
        ]);
    }
}
