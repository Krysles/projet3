<?php
namespace Louvre\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('name', TextType::class)
                ->add('firstName', TextType::class)
                ->add('birthDate', BirthdayType::class, array(
                    'format' => \IntlDateFormatter::SHORT,
                    'widget' => 'single_text'
                ))
                ->add('country', CountryType::class, array(
                    'preferred_choices' => array(
                        'France' => 'FR'
                    )))
                ->add('reducedPrice', CheckboxType::class, array(
                    'label' => 'informations.form.reducedprice',
                    'required' => false
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BookingBundle\Entity\Ticket',
        ));
    }
}