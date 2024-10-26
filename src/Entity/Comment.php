<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "Comments")]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private $id;

    #[ORM\Column(type: 'text')]
    private $commentContent;

    #[ORM\Column(type: 'datetime')]
    private $created_at;
    /*
    #[ORM\Column(type: 'integer')]
    private $user_id;
    */

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private $author;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'commentsOfArticle')]
    private $articleCommented;

    public function __construct()
    {
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
     * Get the value of commentContent
     */
    public function getCommentContent()
    {
        return $this->commentContent;
    }

    /**
     * Set the value of commentContent
     *
     * @return  self
     */
    public function setCommentContent($commentContent)
    {
        $this->commentContent = $commentContent;

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
     * Get the value of articleCommented
     */
    public function getArticleCommented()
    {
        return $this->articleCommented;
    }

    /**
     * Set the value of articleCommented
     *
     * @return  self
     */
    public function setArticleCommented($articleCommented)
    {
        $this->articleCommented = $articleCommented;

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
}
