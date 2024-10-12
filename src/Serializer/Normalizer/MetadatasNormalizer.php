<?php

namespace App\Serializer\Normalizer;

use App\Entity\Chimpokodex;
use App\Entity\Chimpokomon;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MetadatasNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $className = (new \ReflectionClass($object))->getShortName();
        $className = strtolower($className);

        $links = [
            // "all" => $this->urlGenerator->generate("app_" . $className . "__index"),
            // "get" => $this->urlGenerator->generate("app_" . $className . "_show", ["id" => $data['id']]),

            "all" => [
                'url' =>
                $this->urlGenerator->generate("app_" . $className . "__index"),
                "method" => ["GET"]
            ],
            "get" => [
                'url' =>
                $this->urlGenerator->generate("app_" . $className . "_show", ["id" => $data['id']]),
                "method" => ["GET"]
            ],

            "create" => [
                'url' => $this->urlGenerator->generate("app_" . $className . "_new"),
                "method" => ["POST"]
            ],
            "update" => [
                "url" => $this->urlGenerator->generate("app_" . $className . "_edit", ["id" => $data['id']]),
                "method" => ["PUT", "PATCH"]
            ],
            "delete" => [
                "url" => $this->urlGenerator->generate("app_" . $className . "_delete", ["id" => $data['id']]),
                "method" => ["DELETE"]
            ],
        ];

        $data['_links'] = $links;

        // TODO: add, edit  , or delete some data

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Chimpokodex || $data instanceof Chimpokomon;
    }

    public function getSupportedTypes(?string $format): array
    {
        // TODO: return [Object::class => true];
        return [Chimpokodex::class => true, Chimpokomon::class => true];
    }
}
