<?php
namespace App\AdminBundle\Business\Client;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\ClientBundle\Entity\Client;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Util\Canonicalizer as Canonicalizer;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        return new CreateModel();
    }

    public function getOptions()
    {
        return [
            'validation_groups' => 'create'
        ];
    }

    public function buildForm($builder)
    {
        /** @var FormBuilder $builder */
        $builder->add('firstname', 'text', array(
            'attr'     => array(
                'placeholder' => 'FIRST_NAME'
            ),
            'label' => 'First Name',
            'required' => false
        ));
        $builder->add('lastname', 'text', array(
            'attr'     => array(
                'placeholder' => 'LAST_NAME'
            ),
            'label' => 'Last Name',
            'required' => false
        ));
        $builder->add('company', 'text', array(
            'attr'     => array(
                'placeholder' => 'COMPANY'
            ),
            'label' => 'Company',
            'required' => false
        ));
        $builder->add('address1', 'text', array(
            'attr'     => array(
                'placeholder' => 'ADDRESS_1'
            ),
            'label' => 'Address Line 1',
            'required' => false
        ));
        $builder->add('address2', 'text', array(
            'attr'     => array(
                'placeholder' => 'ADDRESS_2'
            ),
            'label' => 'Address Line 2',
            'required' => false
        ));
        $builder->add('city', 'text', array(
            'attr'     => array(
                'placeholder' => 'CITY'
            ),
            'label' => 'City',
            'required' => false
        ));
        $builder->add('state', 'text', array(
            'attr'     => array(
                'placeholder' => 'STATE'
            ),
            'label' => 'State / Region',
            'required' => false
        ));
        $builder->add('postcode', 'text', array(
            'attr'     => array(
                'placeholder' => 'POST_CODE'
            ),
            'label' => 'Post / ZIP Code',
            'required' => false
        ));
        $builder->add('idCountry', 'choice', array(
            'attr'     => array(
                'placeholder' => 'COUNTRY'
            ),
            'label' => 'Country',
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('country')
        ));
        $builder->add('phone', 'text', array(
            'attr'     => array(
                'placeholder' => 'PHONE_NUMBER'
            ),
            'label' => 'Phone Number',
            'required' => false
        ));
        $builder->add('email', 'text', array(
            'attr'     => array(
                'placeholder' => 'EMAIL_ADDRESS'
            ),
            'label' => 'Email',
            'required' => false
        ));
        $builder->add('vatNumber', 'text', array(
            'attr'     => array(
                'placeholder' => 'VAT_NUMBER'
            ),
            'label' => 'VAT Number',
            'required' => false
        ));
        $builder->add('status', 'choice', array(
            'attr'     => array(
                'placeholder' => 'STATE'
            ),
            'label' => 'Status',
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('client_status')
        ));
        $builder->add('plainPassword', 'password', array(
            'attr'     => array(
                'placeholder' => 'PASSWORD',
            ),
            'required' => true
        ));
    }

    public function onSuccess()
    {
        $this->entityManager->flush();
        $model         = $this->getForm()->getData();
        $client        = new Client();
        $canonicalizer = new Canonicalizer();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $client);

        // Set additional fields
        $client->setAddedDate(new \DateTime());

        // Set canonical fields
        $client->setUsername($client->getEmail());
        $client->setUsernameCanonical($canonicalizer->canonicalize($client->getUsername()));
        $client->setEmailCanonical($canonicalizer->canonicalize($client->getEmail()));

        // Set password
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $client->setSalt($salt);
        $encoder_service = $this->container->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($client);
        $encoded_pass = $encoder->encodePassword($model->plainPassword, $salt);
        if(!empty($model->plainPassword)) $client->setPassword($encoded_pass);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}
