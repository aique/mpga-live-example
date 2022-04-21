<?php

namespace App\Controller;

use App\Entity\Category;
use App\Error\JsonResponseError;
use App\Repository\CategoryRepository;
use App\Validation\Category\CreateCategoryValidator;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CategoryRepository $repository;
    private Serializer $serializer;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Category::class);
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @Route("/categories", methods={"GET"}, name="category_list")
     */
    public function categories(): Response
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                $this->repository->findAll(), 'json'
            )
        );
    }

    /**
     * @Route("/categories/{id}", methods={"GET"}, name="category_item", requirements={"id"="\d+"})
     */
    public function category(int $id): Response
    {
        $category = $this->repository->find($id);

        if (!$category) {
            return new Response(
                '', Response::HTTP_NOT_FOUND
            );
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                $category, 'json'
            )
        );
    }

    /**
     * @Route("/categories", methods={"POST"}, name="category_create")
     */
    public function create(Request $request, CreateCategoryValidator $validator, JsonResponseError $error): Response
    {
        $content = $request->getContent();

        $violations = $validator->validate(
            json_decode($content, true)
        );

        if ($violations->count() > 0) {
            return new JsonResponse(
                $error->createResponseFromViolationList($violations),
                Response::HTTP_BAD_REQUEST
            );
        }

        $category = $this->deserializeCategory($content);

        if (!$category) {
            return new JsonResponse(
                $error->createResponseFromMessage('Deserialization error'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return new Response(
            '', Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route("/categories/{id}", methods={"PUT"}, name="category_update", requirements={"id"="\d+"})
     */
    public function update(
        int $id,
        Request $request,
        CreateCategoryValidator $validator,
        JsonResponseError $error
    ): Response {
        $category = $this->repository->find($id);

        if (!$category) {
            return new Response(
                '', Response::HTTP_NOT_FOUND
            );
        }

        $content = $request->getContent();

        $violations = $validator->validate(
            json_decode($content, true)
        );

        if ($violations->count() > 0) {
            return new JsonResponse(
                $error->createResponseFromViolationList($violations),
                Response::HTTP_BAD_REQUEST
            );
        }

        $updatedCategory = $this->deserializeCategory($content);

        if (!$updatedCategory) {
            return new JsonResponse(
                $error->createResponseFromMessage('Deserialization error'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $category->update($updatedCategory);

        $this->entityManager->flush();

        return new Response(
            '', Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route("/categories/{id}", methods={"DELETE"}, name="category_delete", requirements={"id"="\d+"})
     */
    public function delete(int $id): Response {
        $category = $this->repository->find($id);

        if (!$category) {
            return new Response(
                '', Response::HTTP_NOT_FOUND
            );
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return new Response(
            '', Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @Route("/categories", methods={"OPTIONS"}, name="categories_options")
     */
    public function categoriesOptions(): Response {
        return new Response('', Response::HTTP_NO_CONTENT, [
            'Access-Control-Allow-Methods' => 'OPTIONS, GET, POST',
        ]);
    }

    /**
     * @Route("/categories/{id}", methods={"OPTIONS"}, name="category_item_options", requirements={"id"="\d+"})
     */
    public function categoryItemOptions(): Response {
        return new Response('', Response::HTTP_NO_CONTENT, [
            'Access-Control-Allow-Methods' => 'OPTIONS, PUT, DELETE',
        ]);
    }

    private function deserializeCategory($content): ?Category {
        $category = $this->serializer->deserialize(
            $content, Category::class, 'json'
        );

        if (!$category instanceof Category) {
            return null;
        }

        return $category->initialize();
    }
}