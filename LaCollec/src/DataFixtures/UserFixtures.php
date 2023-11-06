<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    // defines reference names for instances of User
    const USER_JEAN_CHARLES = 'jean-charles-account';
    const USER_PEDRO = 'pedro-account';
    
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }
    
    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$userReference,$email,$plainPassword,$role,$memberReference]) {
            $user = new User();
            $member=$this->getReference($memberReference);
            $password = $this->hasher->hashPassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setMember($member);
            
            $roles = array();
            $roles[] = $role;
            $user->setRoles($roles);
            
            $manager->persist($user);
            $this->addReference($userReference, $user);
        }
        $manager->flush();
    }
    private function getUserData()
    {
        yield [
            self::USER_JEAN_CHARLES,
            'jean-charles@localhost',
            'jean-charles',
            'ROLE_USER',
            AppFixtures::JEAN_CHARLES
        ];
        yield [
            self::USER_PEDRO,
            'pedro@localhost',
            'pedro',
            'ROLE_ADMIN',
            AppFixtures::PEDRO
        ];
    }
}
