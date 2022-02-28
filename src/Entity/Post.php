<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post'=>[
            'validation_groups'=>[Post::class, 'validationGroups']
        ]
    ],
    itemOperations: [
        'put',
        'delete',
        'get' => [
            'normalization_context' => ['groups' => ['read:collection', 'read:item', 'read:Post']]
        ]],
    denormalizationContext: ['groups' => ['write:Post']],
    normalizationContext: ['groups' => ['read:collection']],
    paginationMaximumItemsPerPage: 2
),
ApiFilter(SearchFilter::class, properties: ['id'=>'exact','title'=>'partial'])]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:collection', 'write:Post']),
        Length(min: 5, groups:['create:Post'])]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:collection', 'write:Post'])]
    private $slug;

    #[ORM\Column(type: 'text')]
    #[Groups(['read:item', 'write:Post'])]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:item'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'posts')]
    #[Groups(['read:item', 'write:Post'])]
    private $caategory;

    public static function validationGroups(self $post){
        return ['create:Post'];
    }

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCaategory(): ?Category
    {
        return $this->caategory;
    }

    public function setCaategory(?Category $caategory): self
    {
        $this->caategory = $caategory;

        return $this;
    }
}
