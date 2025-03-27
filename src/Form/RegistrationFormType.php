<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    CheckboxType,
    EmailType,
    PasswordType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{
    IsTrue,
    Length,
    NotBlank
};

class RegistrationFormType extends AbstractType
{
    private const MIN_PASSWORD_LENGTH = 8;
    private const MAX_PASSWORD_LENGTH = 120;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'É necessario o email.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Aceito os termos de uso',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Você deve aceitar os termos de uso.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Senha',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => $this->getPasswordConstraints(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms',
        ]);
    }

    /**
     * Generates password validation constraints
     *
     * @return array
     */
    private function getPasswordConstraints(): array
    {
        return [
            new NotBlank([
                'message' => 'Por favor, insira uma senha',
            ]),
            new Length([
                'min' => self::MIN_PASSWORD_LENGTH,
                'max' => self::MAX_PASSWORD_LENGTH,
                'minMessage' => 'Sua senha deve ter pelo menos {{ limit }} caracteres',
                'maxMessage' => 'Sua senha não pode exceder {{ limit }} caracteres',
            ]),
        ];
    }
}
