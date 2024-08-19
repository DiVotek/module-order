<?php

return [
   'payment' => [
      'default' => 'default_payment',
   ],
   'delivery' => [
      'default' => 'default_delivery',
   ],
   'order' => [
      'template' => 'order_template',
      'design' => 'order_design',
      'policy' => 'order_policy',
      'fields' => [
         'name' => 'order_field_name',
         'surname' => 'order_field_surname',
         'email' => 'order_field_email',
         'phone' => 'order_field_phone',
         'comment' => 'order_field_comment',
      ]
   ],
   'cart' => [
      'template' => 'cart_template',
      'design' => 'cart_design',
   ],
   'min_order_price' => 'min_order_price',
   'email_to' => 'email_to',
];
