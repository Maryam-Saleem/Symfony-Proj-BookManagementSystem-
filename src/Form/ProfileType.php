<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'attr'=>['placeholder'=>'YourName'],
                'constraints'=>[
                    new Callback([$this, 'validateFirstName'])]])
            ->add('save', SubmitType::class)
                //    new Regex(['pattern'=>'/^[A-Za-z\s]*$/','message'=>'Plz write your name in letters only.'])]])

        ;
    }

    public function validateFirstName($value, ExecutionContextInterface $context): void
    {
        // If the firstName is not null, validate against the regex pattern
        if ($value !== null && !preg_match('/^[A-Za-z\s]*$/', $value)) {
            $context->buildViolation('Plz write your name in letters only.')
                ->atPath('firstName')
                ->addViolation();
        }
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
