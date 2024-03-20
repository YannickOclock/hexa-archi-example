<?php
    namespace Domain\Blog\ObjectValue;

    use Assert\Assertion;

    class StatusPost
    {
        public const DRAFT = 'draft';
        public const PUBLISHED = 'published';
        public const ARCHIVED = 'archived';

        public function __construct(
            private string $status
        ) {
            Assertion::inArray($status, [self::DRAFT, self::PUBLISHED, self::ARCHIVED]);
        }

        public function getStatus(): string
        {
            return $this->status;
        }

        public function __toString()
        {
            return $this->status;
        }
    }