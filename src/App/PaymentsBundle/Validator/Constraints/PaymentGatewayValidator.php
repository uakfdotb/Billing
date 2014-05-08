<?php
namespace App\PaymentsBundle\Validator\Constraints;

use App\PaymentsBundle\Entity\PaymentGateway as PaymentGatewayEntity;
use App\PaymentsBundle\Manager\PaymentManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PaymentGatewayValidator extends ConstraintValidator
{
    /** @var \App\PaymentsBundle\Manager\PaymentManager */
    protected $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    // This function is no longer used, though it is useful for Spreedly gateways
    public function validate($value, Constraint $constraint)
    {
        /** @var PaymentGatewayEntity $paymentGateway */
        $paymentGateway = $value;

        if(substr($paymentGateway->getType(), 0, 1) == '_')
        {
            $class  = sprintf('\\App\\AdminBundle\\Helper\\Gateway\\%s', ucfirst(str_replace('_', '', $paymentGateway->getType())));
            $verify = call_user_func(array($class, 'verifyCredentials'), $paymentGateway->getCredentials());


            if(count($verify) > 0) {
                foreach($verify as $v) $this->context->addViolationAtPath('credentials', $v);
            } else {
                $paymentGateway->setSafeCredentials($paymentGateway->getCredentials());
            }
            return null;
        }

        $result         = $this->paymentManager->verifyCredentials($paymentGateway);

        if (!isset($result['token'])) {
            return $this->context->addViolationAtSubPath('credentials', $constraint->message);
        }

        if ($paymentGateway->getId()) {
            $tempPaymentGateway = new PaymentGatewayEntity();
            $tempPaymentGateway->setToken($paymentGateway->getToken());
            $this->paymentManager->redact($tempPaymentGateway);
        }

        $paymentGateway->setToken($result['token']);
    }
}