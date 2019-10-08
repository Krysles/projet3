<?php
namespace Louvre\BookingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeInitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                'format' => \IntlDateFormatter::SHORT,
                'label' => 'index.form.date.label',
                'attr' => array(
                    'data-provide' => 'datepicker',
                    'format' => \IntlDateFormatter::SHORT,
                    'data-date-autoclose' => true,
                    'data-date-start-date' => '0d',
                    'data-date-days-of-week-disabled' => '0,2',
                    'data-date-days-of-week-highlighted' => '0,2',
                    'data-date-today-highlight' => true,
                    'data-date-language' => $options['locale'],
                    'data-date-max-view-mode' => 1
                )
            ))
            ->add('nbTickets', ChoiceType::class, array(
                'label' => 'index.form.ticket',
                'choices' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10')
            ))
            ->add('halfDay', ChoiceType::class, array(
                'label' => 'index.form.type.label',
                'choices' => array('index.form.type.days' => false, 'index.form.type.halfday' => true), 'expanded' => true, 'multiple' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\BookingBundle\Entity\Commande',
            'locale' => 'fr'
        ));
    }
}