<?php
    namespace Acme\Model;

    use Acme\Model;
    use Doctrine\ORM\Mapping;

    /**
     * @Entity @Table(name="products")
     */
    class ProductModel
    {
        /** @Id @Column(type="integer") @GeneratedValue */
        protected $id;
        /** @Column(type="string", name="product_name") */
        protected $productName;
        /** @Column(type="datetime", name="created_at") */
        protected $createdAt;
        /** @Column(type="datetime", name="updated_at") */
        protected $updatedAt;

        public function getId()
        {
            return $this->id;
        }

        public function getProductName()
        {
            return $this->productName;
        }

        public function setProductName($productName)
        {
            $this->productName = $productName;
        }

        public function getCreatedAt()
        {
            return $this->createdAt;
        }

        public function setCreatedAt($createdAt)
        {
            return $this->createdAt = $createdAt;
        }

        public function getUpdatedAt()
        {
            return $this->updatedAt;
        }

        public function setUpdatedAt($updatedAt)
        {
            return $this->updatedAt = $updatedAt;
        }
    }