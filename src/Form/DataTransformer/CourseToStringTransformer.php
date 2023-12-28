<?php

namespace App\Form\DataTransformer;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CourseToStringTransformer implements DataTransformerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($course): string
    {
        if (null === $course) {
            return '';
        }

        return $course->getId();
    }

    public function reverseTransform($courseId): ?Course
    {
        // no issue number? It's optional, so that's ok
        if (!$courseId) {
            return null;
        }

        $course = $this->entityManager
            ->getRepository(Course::class)
            // query for the issue with this id
            ->find($courseId);

        if (null === $course) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'Курс с id = "%s" не существует.',
                $courseId
            ));
        }

        return $course;
    }
}
