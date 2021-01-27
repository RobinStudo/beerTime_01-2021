<?php
namespace App\Form;

use App\Entity\Event;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new DateTime();

        $builder
            ->add('name', null, array(
                'label' => "Nom l'événement",
                'attr' => array(
                    'placeholder' => 'Cours de brassage, dégustation, ...',
                ),
            ))
            ->add('description', null, array(
                'label' => 'Descirption',
                'attr' => array(
                    'rows' => 4,
                ),
                'help' => 'Entre 20 et 600 caractères',
            ))
            ->add('startAt', null, array(
                'label' => 'Date et heure de début',
                'date_widget' => 'single_text',
                'time_widget' => 'choice',
                'minutes' => range(0, 45, 15),
            ))
            ->add('endAt', null, array(
                'label' => 'Date et heure de fin',
                'date_widget' => 'single_text',
                'time_widget' => 'choice',
                'minutes' => range(0, 45, 15),
            ))
            ->add('picture', UrlType::class, array(
                'label' => "URL de l'image",
                'invalid_message' => "L'URL de l'image n'est pas valide",
            ))
            ->add('price', null, array(
                'label' => 'Prix',
                'help' => 'Laisser vide pour les événements gratuits',
            ))
            ->add('capacity', null, array(
                'label' => 'Capacité',
                'help' => 'Laisser vide pour un nombre de place ilimité',
                'attr' => array(
                    'min' => 1,
                ),
            ))
            ->add('place', null, array(
                'label' => "Lieu de l'événement",
                'choice_label' => 'name',
            ))
            ->add('categories', null, array(
                'label' => 'Catégories',
                'choice_label' => 'name',
                'expanded' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
