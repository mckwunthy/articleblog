<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: "Articles")]
class Article
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: 'string')]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'string')]
    private $imgUrl;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'string')]
    private $slug;
    /*
    #[ORM\Column(type: 'integer')]
    private $user_id;
    */

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    private $author;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'articleCommented')]
    private $commentsOfArticle;

    public function __construct()
    {
        $this->commentsOfArticle = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $slug = (new Slugify())->slugify($this->title);
        $this->setSlug($slug);
        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of imgUrl
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set the value of imgUrl
     *
     * @return  self
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreated_at()
    {
        return $this->created_at->format("Y-m-d H:i:s");
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /*set and get user_id place*/

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of commentsOfArticle
     */
    public function getCommentsOfArticle()
    {
        return $this->commentsOfArticle;
    }

    /**
     * Set the value of commentsOfArticle
     *
     * @return  self
     */
    public function setCommentsOfArticle($commentsOfArticle)
    {
        $this->commentsOfArticle = $commentsOfArticle;

        return $this;
    }

    /**
     * Add comment to comments arrayCollection
     * this function not allow a repeat comment
     *
     * @return  arrayCollection
     */
    public function addCommentsOfArticleUnique(Comment $comment)
    {
        if (!$this->commentsOfArticle->contains($comment)) {
            $this->commentsOfArticle->add($comment);
            $comment->setArticleCommented($this);
        }
        return $this;
    }

    /**
     * Add comment to comments arrayCollection
     *
     * @return  arrayCollection
     */
    public function addCommentsOfArticle(Comment $comment)
    {
        // if (!$this->commentsOfArticle->contains($comment)) {
        $this->commentsOfArticle->add($comment);
        $comment->setArticleCommented($this);
        // }
        return $this;
    }

    /**
     * Remove comment from comments arrayCollection
     *
     * @return  arrayCollection
     */
    public function removeCommentsOfArticle(Comment $comment)
    {
        if ($this->commentsOfArticle->removeElement($comment)) {
            if ($comment->getArticleCommented() === $this) {
                $comment->setArticleCommented(null);
            }
        }
        return $this;
    }

    /**
     * Get the value of slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
