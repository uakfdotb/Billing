<?php
namespace App\PaymentsBundle\Form;

use App\PaymentsBundle\Entity\PaymentGateway;
use App\PaymentsBundle\Manager\PaymentManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentGatewayType extends AbstractType
{
    /** @var \App\PaymentsBundle\Manager\PaymentManager */
    protected $paymentManager;

    /** @var array */
    protected $gatewayData;

    /** @var FormFactory */
    protected $formFactory;

    /** @var  array */
    protected $safeCredentials = [];

    function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;

        // we do it in the constructor mainly because we are using it everytime.
        $this->gatewayData = $this->paymentManager->getPaymentGateways();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->formFactory = $builder->getFormFactory();

        $paymentGateway = $builder->getData();
        // we build the form based on the data from gateways.xml and we'll use a pre-bind, post-bind transforming process
        // to store and re-store the login information which is specific to every payment gateway

        if (!($paymentGateway instanceof PaymentGateway) || !$paymentGateway->getId()) {
            $builder->add('type', 'choice', array(
                'choices'  => $this->paymentManager->getGatewayNamesChoices(),
                'required' => true,
            ));
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        /** @var PaymentGateway $paymentGateway */
        $paymentGateway = $event->getData();

        if ($paymentGateway === null) {
            return null;
        }
        $gatewayData = $this->paymentManager->getGateway($paymentGateway->getType());

        $authMode = null;
        // no gateway has a more than a single auth_code, even if it has we'll use the default one
        if (isset($gatewayData['auth_modes']) && isset($gatewayData['auth_modes']['auth_mode'])) {
            if (isset($gatewayData['auth_modes']['auth_mode']['name'])) {
                $gatewayDataArray = array($gatewayData['auth_modes']['auth_mode']);
            } else {
                $gatewayDataArray = $gatewayData['auth_modes']['auth_mode'];
            }
        } else {
            $gatewayDataArray = array();
        }
        $authMode = null;
        foreach ($gatewayDataArray as $_authMode) {
            if ($_authMode['auth_mode_type'] == 'default') {
                $authMode = $_authMode;
            }
        }

        if ($authMode === null) {
            reset($gatewayDataArray);
            $authMode = current($gatewayDataArray);
        }

        if ($gatewayData['gateway_type'] !== 'test' && $authMode === null) {
            throw new \Exception("Default auth mode not found, this is the gateway: \n" . print_r($gatewayData, true));
        }

        /** @var Form $credentialsType */
        $credentialsType = $this->formFactory->createNamed('credentials', 'form', null, array(
            'mapped' => true
        ));

        if ($authMode) {
            if (isset($authMode['credentials']) && isset($authMode['credentials']['credential'])) {
                if (isset($authMode['credentials']['credential']['name'])) {
                    $authModeArray = array($authMode['credentials']['credential']);
                } else {
                    $authModeArray = $authMode['credentials']['credential'];
                }
                foreach ($authModeArray as $credential) {
                    if (!isset($credential['name'])) {
                        continue;
                    }

                    if ($credential['safe'] == 'true') {
                        $this->safeCredentials[] = $credential['name'];
                    }

                    $credentialsType->add(
                        $this->formFactory->createNamed(
                            $credential['name'],
                            ($credential['safe'] == 'true') ? 'text' : 'password',
                            null,
                            array(
                                'mapped' => false,
                                'label' => $credential['label']
                            )
                        )
                    );
                }
            }
        }

        $form->add($credentialsType);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\PaymentsBundle\Entity\PaymentGateway'
        ]);
    }

    public function getName()
    {
        return 'payment_gateway';
    }
}