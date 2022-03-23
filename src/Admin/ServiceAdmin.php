<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Admin;

/**
 * Description of ServiceAdmin
 *
 * @author trieu
 */
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Services;
use App\Entity\ShopService;

class ServiceAdmin extends AbstractAdmin {

    private $container;

    protected function configureFormFields(FormMapper $form): void {
        $form->add('Name', TextType::class, ['required' => true]);
        $form->add('Description', TextareaType::class, ['required' => false]);
        $form->add('Active', ChoiceType::class, ['required' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void {
        $datagrid->add('Name');
        $datagrid->add('Description');
        $datagrid->add('Active');
    }

    protected function configureListFields(ListMapper $list): void {
        $list->addIdentifier('id', null, ['route' => ['name' => 'edit']]);
        $list->addIdentifier('Name', null, ['route' => ['name' => 'edit']]);
        $list->add('Description');
        $list->add('Active');
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
        $this->container = $container;
    }

    protected function preRemove(object $object): void {
       $shopServices =  $this->getModelManager(ShopService::class)->findBy(ShopService::class,['Service'=>$object->getId()]);
       if(count($shopServices) > 0){
           foreach($shopServices as $shopservice){
               $object->removeService($shopservice);
           }
       }
      
    }

}
