<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Locale;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
     * @Route("/form", name="form")
     */
    public function index()
    {
        return $this->render('form/index.html.twig', [
            'controller_name' => 'FormController',
        ]);
    }

    /**
     * @Route("/form/success", name="task_success")
     */
    public function success()
    {
        return $this->render('form/success.html.twig');
    }

    /**
     * @Route("/form/01", name="form_01")
     */
    public function form01(Request $request)
    {
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add( 'url', UrlType::class, array(
                'invalid_message' => 'That is not a valid issue url',
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($task);
                $entityManager->flush();

                $this->addFlash('success', 'Enregistrement fait.');

            } catch( \Exception $e) {
                $this->addFlash('danger', 'ERREUR : Enregistrement non fait.');
            }

            return $this->redirectToRoute('task_success');
        }

        return $this->render('form/form01.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/form/02", name="form_02")
     */
    public function form02(Request $request)
    {
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        //-----------------------------------------------
        // FORM
        //-----------------------------------------------
        $form = $this->createFormBuilder($task)

            //-----------------------------------------------
            // TEXT FIELDS
            //-----------------------------------------------

            // TEXT (string)
            ->add('task', TextType::class, array(
                'attr' => array('class'=>'xxx'),
                //'data' => 'lorem ipsum',
                'disabled' => false,
                'empty_data' => 'John DOE',
                'help' => 'Help message here',
                'required' => true,
                'trim' => true,

                // Label :
                'label' => 'Label custom',
                'label_attr' => array('class' => 'label-xxx'),
                //'label_format' => 'form.task.%name% (form.task.%id%)',

                //'mapped' => false, // clean data object
            ))

            // DATE TIME (datetime)
            ->add('dueDate', DateType::class, array(
                'widget' => 'single_text',
            ))

            // EMAIL (string)
            ->add('email', EmailType::class, array(
                'trim' => true,
            ))

            // AGE (int)
            ->add('age', IntegerType::class, array(
                'trim' => true,
            ))

            // PRICE (float)
            ->add('price', MoneyType::class, array(
                'trim' => true,
                'currency' => 'USD', // https://en.wikipedia.org/wiki/ISO_4217
                'invalid_message' => 'You entered an invalid value, it should include %num% letters (%toto%)',
                'invalid_message_parameters' => array('%num%' => 6, '%toto%' => 'titi'),
            ))

            // NUMBER (float)
            ->add('number', NumberType::class, array(
                'trim' => true,
                'grouping' => false,
                'scale' => 2, // use 22.55223344 - if 2, ex : 22.55. if 3, ex : 22.552.
                'error_bubbling' => true, // true:Error global form, false:Error top field.
                'invalid_message' => 'You entered an invalid value, it should include %num% letters (%toto%)',
                'invalid_message_parameters' => array('%num%' => 6, '%toto%' => 'titi'),
            ))

            // https://symfony.com/doc/current/reference/forms/types/password.html
            // PASSWORD (string)
            ->add('password', PasswordType::class, array(
                'trim' => true,
                'always_empty' => true,
            ))

            // PERCENT (float)
            ->add('percent', PercentType::class, array(
                'trim' => true,
                'scale' => 5,
                'type' => 'integer', // fractional or integer
                'invalid_message' => 'You entered an invalid value, it should include only number with "." separate',
                //'invalid_message_parameters' => array('%num%' => 6, '%toto%' => 'titi'),
                'data' => 5.5,
            ))

            // SEARCH (string)
            ->add('search', SearchType::class, array(
                'trim' => true,
            ))

            // URL (string)
            ->add('url', UrlType::class, array(
                'trim' => true,
                'default_protocol' => 'http',
            ))

            // RANGE (string)
            ->add('rangeminmax', RangeType::class, array(
                'trim' => true,
                'attr' => array(
                    'min' => 5,
                    'max' => 15,
                )
            ))

            // TEL (string)
            ->add('tel', TelType::class, array(
                'trim' => true,
            ))

            // COLOR (string)
            ->add('color', ColorType::class, array(
                'trim' => true,
            ))

            // TEXTAREA (text)
            ->add('message', TextareaType::class, array(
                'trim' => true,
            ))

            //-----------------------------------------------
            // CHOICE FIELDS : select, radio or checbox (selon choix "expanded" ou "multiple"
            //-----------------------------------------------

            // CHOICE (string)
            // https://symfony.com/doc/current/reference/forms/types/choice.html
            ->add('choice', ChoiceType::class, array(
                'choices'  => array(
                    'Maybe' => null,
                    'Yes' => true,
                    'No' => false,
                ),

                // Selon choix "expanded" ou "multiple" = checkbox, radio ou select
                'expanded' => true,
                'multiple' => false,
            ))

            // ENTITY
            // https://symfony.com/doc/current/reference/forms/types/entity.html
            // @todo : A tester (form type entity).

            // COUNTRY (string)
            // https://symfony.com/doc/current/reference/forms/types/country.html
            ->add('country', CountryType::class, array(
                'placeholder' => '--- Choose a country ---',
                'preferred_choices' => array('FR', 'ES', 'IT', 'DE'),

                // Selon choix "expanded" ou "multiple" = checkbox, radio ou select
                'expanded' => false,
                'multiple' => false,
            ))

            // LANGUAGE (string)
            // https://symfony.com/doc/current/reference/forms/types/language.html
            ->add('language', LanguageType::class, array(
                'placeholder' => '--- Choose a language ---',
                'preferred_choices' => array('fr', 'en'),

                // Selon choix "expanded" ou "multiple" = checkbox, radio ou select
                'expanded' => false,
                'multiple' => false,
            ))

            // LOCALE (string)
            // https://symfony.com/doc/current/reference/forms/types/locale.html
            ->add('localeABC', LocaleType::class, array(
                'placeholder' => '--- Choose a locale ---',
                'preferred_choices' => array('fr', 'en'),

                // Selon choix "expanded" ou "multiple" = checkbox, radio ou select
                'expanded' => false,
                'multiple' => false,
            ))

            // TIMEZONE (string)
            // https://symfony.com/doc/current/reference/forms/types/timezone.html
            ->add('timezone', TimezoneType::class, array(
                'placeholder' => '--- Choose a timezone ---',
                'regions' => \DateTimeZone::EUROPE,

                // Selon choix "expanded" ou "multiple" = checkbox, radio ou select
                'expanded' => false,
                'multiple' => false,
            ))

            // CURRENCY (string)
            // https://symfony.com/doc/current/reference/forms/types/currency.html
            ->add('currency', CurrencyType::class, array(
                'placeholder' => '--- Choose a currency ---',


                // Selon choix "expanded" ou "multiple" = checkbox, radio ou select
                'expanded' => false,
                'multiple' => false,
            ))

            //-----------------------------------------------
            // DATE / TIME
            //-----------------------------------------------

            // DATE (string)
            // https://symfony.com/doc/current/reference/forms/types/date.html
            ->add('date', DateType::class, array(
                'widget' => 'choice', // choice, text, single_text
                //'html5' => false,
                'input' => 'datetime', // string, datetime, datetime_immutable, array, timestamp
                'days' => range(1,25),
                'months' => range(1,6),
                'years' => range(1960, 2018),
                'placeholder' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),
                'format' => 'dd-MM-yyyy',
            ))

            // @todo-michael : DateIntervalType.
            /*
            // DATE (string)
            // https://symfony.com/doc/current/reference/forms/types/dateinterval.html
            ->add('DateInterval', DateIntervalType::class, array(
                'widget' => 'choice', // choice, text, integer, single_text
                'input' => 'string', // string, dateinterval, array
                'days' => range(1,25),
                'months' => range(1,6),
                'years' => range(1960, 2018),
                'placeholder' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),

                // customize which text boxes are shown
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => true,
                'with_hours'  => true,
            ))
            */

            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
        }

        return $this->render('form/form02.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
