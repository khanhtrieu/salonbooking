<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Admin;

/**
 * Description of ShopAdmin
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
use App\Entity\Shop;

class ShopAdmin extends AbstractAdmin {

    private $container;

    protected function configureFormFields(FormMapper $form): void {
        $form->add('Name', TextType::class, ['required' => true]);
        $form->add('Description', TextareaType::class, ['required' => false]);
        $form->add('Address1', TextType::class, ['required' => false]);
        $form->add('Address2', TextType::class, ['required' => false]);
        $form->add('City', TextType::class, ['required' => false]);
        $form->add('State', TextType::class, ['required' => false]);
        $form->add('ZipCode', TextType::class, ['required' => false]);
        $form->add('Country', CountryType::class, ['required' => false]);
        $form->add('Active', ChoiceType::class, ['required' => true,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void {
        $datagrid->add('Name');
        $datagrid->add('Address1');
        $datagrid->add('Address2');
        $datagrid->add('City');
        $datagrid->add('State');
        $datagrid->add('ZipCode');
        $datagrid->add('Country');
        $datagrid->add('Active');
    }

    protected function configureListFields(ListMapper $list): void {
        $list->addIdentifier('id', null, ['route' => ['name' => 'edit']]);
        $list->addIdentifier('Name', null, ['route' => ['name' => 'edit']]);
        $list->add('Address1');
        $list->add('Address2');
        $list->add('City');
        $list->add('State');
        $list->add('ZipCode');
        $list->add('Country');
        $list->add('Active');
    }

    public function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container) {
        $this->container = $container;
    }

}
