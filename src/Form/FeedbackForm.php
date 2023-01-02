<?php

namespace App\Form;

class FeedbackForm
{
    protected ?string $name;
    protected string $email;
    protected string $message;
    protected ?string $phone;
    protected $reCaptcha;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getReCaptcha()
    {
        return $this->reCaptcha;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param mixed $reCaptcha
     */
    public function setReCaptcha($reCaptcha): void
    {
        $this->reCaptcha = $reCaptcha;
    }
}