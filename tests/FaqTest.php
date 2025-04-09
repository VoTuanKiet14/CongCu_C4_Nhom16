<?php

use PHPUnit\Framework\TestCase;

class FaqTest extends TestCase
{
    protected $faq;

    protected function setUp(): void
    {
        $this->faq = new Faq();
    }

    public function testFaqCreation()
    {
        $this->faq->setTitle("What is blood donation?");
        $this->faq->setDescription("Blood donation is the process of voluntarily giving blood.");
        $this->faq->setTimestamp(new DateTime());

        $this->assertEquals("What is blood donation?", $this->faq->getTitle());
        $this->assertEquals("Blood donation is the process of voluntarily giving blood.", $this->faq->getDescription());
        $this->assertInstanceOf(DateTime::class, $this->faq->getTimestamp());
    }

    public function testFaqDescriptionLength()
    {
        $this->faq->setDescription(str_repeat("A", 256)); // Exceeding length

        $this->expectException(LengthException::class);
        $this->faq->validate();
    }

    public function testFaqTitleNotEmpty()
    {
        $this->faq->setTitle("");

        $this->expectException(InvalidArgumentException::class);
        $this->faq->validate();
    }
}