<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Minsal\SiapsBundle\Entity\MntEmpleado;

class UserAdmin extends BaseUserAdmin {

    public function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->with('Datos Usuario')
                ->add('firstname', null, array('required' => true))
                ->add('lastname', null, array('required' => true))
                ->add('username')
                ->add('email', null, array('required' => true))
                ->add('plainPassword', 'repeated', array(
                    'required' => true,
                    'type' => 'password',
                    'first_options' => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Confirmación de contraseña'),
                    'invalid_message' => 'Las contraseñas deben coincidir, vuelva a digitarla',
                ))
                ->add('enabled', null, array('required' => true))
                ->add('groups', 'sonata_type_model', array('required' => true, 'expanded' => true,
                    'multiple' => true,
                    'by_reference' => true))
                ->add('roles', 'sonata_security_roles', array('multiple' => true))
                ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('username')
                ->add('groups')
                ->add('enabled', null, array('editable' => true))
                ->add('createdAt')
        ;
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'edit':
                return 'ApplicationSonataUserBundle:UserAdmin:edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    /**
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface
     */
    public function createQuery($context = 'list') {
        $query = parent::createQuery($context);

        return $query;
    }

   
}
