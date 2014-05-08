<?php
namespace App\PaymentsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PaymentGateway extends Constraint
{
    public $message = 'Invalid authentication data';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        #return get_class($this) . 'Validator';
        return 'payments.gateway.validator';
    }
}