<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Annotation\JsonBody;
use App\Exception\ValidationException;
use Doctrine\Common\Annotations\Reader as AnnotationReader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AnnotationSubscriber implements EventSubscriberInterface
{
    private $reader;
    private $serializer;
    private $validator;

    public function __construct(AnnotationReader $reader, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->reader = $reader;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['handleAnnotation', 1024],
            ],
        ];
    }

    public function handleAnnotation(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) { //return if no controller
            return;
        }

        $request = $event->getRequest();
        $object = new \ReflectionObject($controller[0]); // get controller
        $method = $object->getMethod($controller[1]); // get method
        $annotation = $this->reader->getMethodAnnotation($method, JsonBody::class);

        //No annotation
        if (null === $annotation) {
            return;
        }

        $parameterFound = null;
        $type = $annotation->getClass();
        $name = $annotation->getPropertyName();

        foreach ($method->getParameters() as $parameter) {
            // Type and name are declared in the annotation
            if ($type !== null && $parameter->getName() === $name) {
                $type = new \ReflectionClass($type);
                $parameterFound = $parameter;
                break;
            }

            // Type is not declared but name is
            if ($type === null && $parameter->getClass() !== null && $parameter->getName() === $name) {
                $type = $parameter->getClass();
                $parameterFound = $parameter;

                break;
            }

            // Type and name are not declared
            if ($type === null && $name === null) {
                if ($parameter->getClass() === null || $parameter->getClass()->getName() !== Request::class) {
                    $type = $parameter->getClass();
                    $parameterFound = $parameter;

                    break;
                }
            }
        }

        if ($parameterFound === null) {
            throw new \InvalidArgumentException('No parameter was found to handle the JsonBody');
        }

        $content = (string) $request->getContent();

        try {
            $object = $this->serializer->deserialize(
                $content,
                $type->getName(),
                'json'
            );
        } catch (\Exception $e) {
            throw new BadRequestHttpException(
                'Cannot create an instance of ' . $type->getName() . ' from serialized data ' . $content,
                $e
            );
        }

        $violations = $this->validator->validate($object);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $request->attributes->set($parameterFound->getName(), $object);
    }
}
