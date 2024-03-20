<?php
    namespace Domain\Blog\ObjectValue;

    use Assert\Assertion;
    use Assert\AssertionFailedException;

    class StatusPost
    {
        public const string DRAFT = 'draft';
        public const string PUBLISHED = 'published';
        public const string ARCHIVED = 'archived';

        /**
         * @throws AssertionFailedException
         */
        public function __construct(
            private readonly string $status
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