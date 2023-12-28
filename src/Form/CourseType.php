<?php

 namespace App\Form;

 use App\Entity\Course;
 use Symfony\Component\Form\AbstractType;
 use Symfony\Component\Form\Extension\Core\Type\TextareaType;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Component\Form\FormBuilderInterface;
 use Symfony\Component\OptionsResolver\OptionsResolver;
 use Symfony\Component\Validator\Constraints\Length;
 use Symfony\Component\Validator\Constraints\NotBlank;

 class CourseType extends AbstractType
 {
     public function buildForm(FormBuilderInterface $builder, array $options): void
     {
         $builder
             ->add('code', TextType::class, [
                 'label' => 'Символьный код',
                 'constraints' => [
                     new NotBlank(message: 'Символьный код не может быть пустым'),
                     new Length(max: 255, maxMessage: 'Символьный код должен быть не более 255 символов')
                 ]
             ])
             ->add('name', TextType::class, [
                 'label' => 'Название',
                 'constraints' => [
                     new NotBlank(message: 'Название не может быть пустым'),
                     new Length(max: 255, maxMessage: 'Название должно быть не более 255 символов')
                 ]
             ])
             ->add('description', TextareaType::class, [
                 'label' => 'Описание',
                 'required' => false,
                 'constraints' => [
                     new Length(max: 1000, maxMessage: 'Описание должно быть не более 1000 символов')
                 ]
             ])
         ;
     }

     public function configureOptions(OptionsResolver $resolver): void
     {
         $resolver->setDefaults([
             'data_class' => Course::class,
         ]);
     }
 }