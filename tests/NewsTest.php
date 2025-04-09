<?php

use PHPUnit\Framework\TestCase;

class NewsTest extends TestCase
{
    protected $news;

    protected function setUp(): void
    {
        $this->news = new News();
    }

    public function testTitleCanBeSetAndGet()
    {
        $this->news->setTitle("Blood Donation Event");
        $this->assertEquals("Blood Donation Event", $this->news->getTitle());
    }

    public function testContentCanBeSetAndGet()
    {
        $this->news->setContent("Join us for a blood donation event this weekend.");
        $this->assertEquals("Join us for a blood donation event this weekend.", $this->news->getContent());
    }

    public function testAuthorCanBeSetAndGet()
    {
        $this->news->setAuthor("John Doe");
        $this->assertEquals("John Doe", $this->news->getAuthor());
    }

    public function testImageUrlCanBeSetAndGet()
    {
        $this->news->setImageUrl("http://example.com/image.jpg");
        $this->assertEquals("http://example.com/image.jpg", $this->news->getImageUrl());
    }

    public function testTimestampCanBeSetAndGet()
    {
        $timestamp = new DateTime();
        $this->news->setTimestamp($timestamp);
        $this->assertEquals($timestamp, $this->news->getTimestamp());
    }
}