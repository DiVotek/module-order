<?php

namespace Modules\Order\Services;

class CheckoutField
{
   public const FIELD_PREFIX = 'userData.';
   public string $name;

   public string $label;

   public bool $is_required;

   public ?string $mask;

   public ?string $type;

   public ?string $placeholder;

   public array $rules;

   public string $tag;

   public function __construct(
      string $name,
      ?string $label = null,
      ?string $placeholder = null,
      bool $is_required = false,
      ?string $mask = null,
      string $type = 'text',
      array $rules = [],
      string $tag = 'textarea'
   ) {
      $this->name = $name;
      $this->is_required = $is_required;
      $this->label = ucfirst($label ?? $name);
      $this->mask = $mask;
      $this->type = $type;
      $this->placeholder = ucfirst($placeholder ?? $label ?? $name);
      $this->rules = $rules;
      if ($is_required) {
         $this->rules[] = 'required';
      }
      $this->tag = $tag;
   }
   public function validate(): array
   {
      return [self::FIELD_PREFIX . $this->name => $this->getRules()];
   }

   public function getName(): string
   {
      return self::FIELD_PREFIX .  $this->name;
   }

   public function getRules(): ?string
   {
      return $this->rules ? implode('|', $this->rules) : null;
   }


   public function getAttributes(): string
   {
      return 'name=' . $this->name . ' required=' . $this->is_required . ' type=' . $this->type . ' placeholder=' . $this->placeholder . ' id=checkout_field_' . $this->name . ' wire:model.defer=userData.' . $this->name;
   }
}
