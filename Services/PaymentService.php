<?php

namespace Modules\Order\Services;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionClass;
use Modules\Order\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Modules\Order\Services\PaymentStatus\StatusPending;

use Modules\Order\Services\PaymentStatus\StatusUnexpected;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;
use Modules\Order\Services\PaymentStatus\StatusDontNeed;

class PaymentService
{
   private Order $order;

   private PaymentMethodInterface $paymentMethod;

   public function __construct(Order $order)
   {
      $this->order = $order;
      $this->loadPaymentMethods();
   }

   public function pay(): RedirectResponse|Redirector
   {
      if ($this->paymentMethod->shouldProceed()) {
         $url = $this->processPayment();
         if ($url) {
            return Redirect::away($url);
         }
      } else {
         $status = new StatusDontNeed();
         $this->updateOrderPaymentStatus($status);
      }

      return Redirect::to($this->getRedirectUrl($this->order));
   }

   private function processPayment(): string
   {
      $preparedData = $this->paymentMethod->createOrderData($this->order);
      try {
         $response = Http::withHeaders([...$preparedData->getHeaders(), 'Content-Type' => 'application/json'])
            ->withBody(json_encode($preparedData->getParams()), 'application/json')
            ->post($this->paymentMethod->createUrl());
         if ($response->successful()) {
            $orderId = $this->paymentMethod->getOrderIdFromCreateResponse((array)$response->json());
            $this->updateOrderInvoiceId($orderId);
            return $this->paymentMethod->getRedirectLinkFromCreateResponse((array)$response->json());
         } else {
            Log::channel('payment')->error($response->json());
         }
      } catch (Exception $e) {
         Log::channel('payment')->error($e->getMessage());
      }
      return '';
   }

   private function updateOrderInvoiceId(int|string $invoiceId): void
   {
      $this->order->payment_order_id = $invoiceId;
      $this->order->payment_status =  (new StatusPending)->getName();
      $this->order->save();
   }

   public function checkOrderStatus(): void
   {
      if (! $this->paymentMethod->shouldProceed()) {
         return;
      }
      $preparedData = $this->paymentMethod->statusOrderData($this->order);
      try {
         $req = Http::withHeaders($preparedData->getHeaders());
         $url = $this->paymentMethod->statusUrl($this->order);
         if ($this->paymentMethod->statusMethod() === 'POST') {
            $response = $req->post($url, $preparedData->getParams());
         } else {
            $response = $req->get($url, $preparedData->getParams());
         }
         if ($response->successful()) {
            $status = $this->paymentMethod->getStatusFromStatusResponse((array)$response->json());
            $this->updateOrderPaymentStatus($status);
         }
      } catch (Exception $e) {
         Log::channel('payment')->error($e->getMessage());
      }
   }

   private function updateOrderPaymentStatus(PaymentStatusInterface $status): void
   {
      // PaymentHistory::create([
      //     'order_id' => $this->order->id,
      //     'data' => $status->getId(),
      //     'status' => $status->getName(),
      // ]);
      $this->order->payment_status = $status->getName();
      $this->order->save();
   }

   private function getRedirectUrl(Order $order): string
   {
      return thank_slug($order->uuid);
   }

   private function loadPaymentMethods(): void
   {
      $paymentMethods = [];
      $directory = module_path('Order', 'Services/Payment');
      $files = File::files($directory);
      foreach ($files as $file) {
         $className = 'Modules\\Order\\Services\\Payment\\' . pathinfo($file, PATHINFO_FILENAME);
         if (class_exists($className)) {
            $reflection = new ReflectionClass($className);
            if ($reflection->implementsInterface(PaymentMethodInterface::class) && !$reflection->isAbstract()) {
               $paymentMethods[] = $reflection->newInstance();
            }
         } else {
            require_once $file->getPathname();
            if (class_exists($className)) {
               $reflection = new ReflectionClass($className);
               if ($reflection->implementsInterface(PaymentMethodInterface::class) && !$reflection->isAbstract()) {
                  $paymentMethods[] = $reflection->newInstance();
               }
            }
         }
      }
      $this->setCurrentPaymentMethods($paymentMethods);
   }

   private function setCurrentPaymentMethods(array $paymentMethods): void
   {
      foreach ($paymentMethods as $paymentMethod) {
         if ($paymentMethod->getId() === $this->order->payment_method_id) {
            $this->paymentMethod = $paymentMethod;
            break;
         }
      }
   }
}
