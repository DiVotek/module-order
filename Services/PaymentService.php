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
use Modules\Order\Models\PaymentMethod;
use Illuminate\Support\Facades\Redirect;
use function Modules\Order\Providers\thank_slug;
use Modules\Order\Services\PaymentStatus\StatusPending;

use Modules\Order\Services\PaymentStatus\StatusUnexpected;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;

class PaymentService
{
   private Order $order;

   private PaymentMethod $paymentMethod;

   public function __construct(Order $order)
   {
      $this->order = $order;
      // $this->loadPaymentMethods();
      $this->paymentMethod = PaymentMethod::first() ?? new PaymentMethod;
   }

   public function pay(): RedirectResponse|Redirector
   {
      return Redirect::to($this->getRedirectUrl($this->order));
      $url = $this->processPayment();
      if ($url && $this->paymentMethod->getShouldBeProcessed()) {
         return Redirect::away($url);
      } else {
         $status = new StatusUnexpected;
         $this->updateOrderPaymentStatus($status);
      }

      return Redirect::to($this->getRedirectUrl($this->order));
   }

   private function processPayment(): string
   {
      $preparedOrder = $this->paymentMethod->prepareOrder($this->order);
      $additionalParams = $this->paymentMethod->getCreateOrderRequestData();
      try {
         $response = Http::withHeaders([...$additionalParams->getHeaders(), 'Content-Type' => 'application/json'])
            ->withBody(json_encode([...$preparedOrder, ...$additionalParams->getParams()]), 'application/json')
            ->post($this->paymentMethod->getUrlForCreateRequest());
         if ($response->successful()) {
            $orderId = $this->paymentMethod->getOrderIdFromResponse($response->json());
            $this->updateOrderInvoiceId($orderId);

            return $this->paymentMethod->getRedirectLinkFromResponse($response->json()) ?? '';
         } else {
            Log::channel('payment')->error($response->json());
         }

         return '';
      } catch (Exception $e) {
         Log::channel('payment')->error($e->getMessage());
      }

      return '';
   }

   private function updateOrderInvoiceId(int|string $invoiceId): void
   {
      $details = $this->order->details;
      $details['payment_invoice'] = $invoiceId;
      $this->order->details = $details;
      $status = new StatusPending;
      if (! $this->paymentMethod->getShouldBeProcessed()) {
         $status = new StatusUnexpected;
      }
      $this->order->save();
      $this->updateOrderPaymentStatus($status);
   }

   public function checkOrderStatus(): void
   {
      $additionalParams = $this->paymentMethod->getStatusOrderRequestData($this->order);
      if (! $this->paymentMethod->getShouldBeProcessed()) {
         return;
      }
      try {
         $req = Http::withHeaders($additionalParams->getHeaders());
         if ($this->paymentMethod->getStatusRequestMethod() === 'POST') {
            $response = $req->post($this->paymentMethod->getUrlForStatusRequest($this->order), $additionalParams->getParams());
         } else {
            $response = $req->get($this->paymentMethod->getUrlForStatusRequest($this->order), $additionalParams->getParams());
         }
         if ($response->successful()) {
            $statusParamName = $this->paymentMethod->getStatusParamNameFromStatusResponse();
            $status = $this->paymentMethod->orderStatusToPaymentStatus($response->json()[$statusParamName]);
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
      $this->order->payment_status = $status->getId();
      $this->order->save();
   }

   private function getRedirectUrl(Order $order): string
   {
      return tRoute('slug', [
         'number' => $order->number,
         'slug' => thank_slug(),
      ]);
   }

   private function getWebhookUrl(): string
   {
      return '';

      return route('payment-webhook', ['number' => $this->order->number]);
   }

   private function loadPaymentMethods(): void
   {
      $paymentMethods = [];
      $directory = app_path('Payment/PaymentMethods');
      $files = File::files($directory);
      foreach ($files as $file) {
         $className = 'App\\Payment\\PaymentMethods\\' . pathinfo($file, PATHINFO_FILENAME);
         if (class_exists($className)) {
            $reflection = new ReflectionClass($className);
            if ($reflection->isSubclassOf(PaymentMethod::class) && ! $reflection->isAbstract()) {
               $paymentMethods[] = $reflection->newInstance();
            }
         } else {
            require_once $file->getPathname();
            if (class_exists($className)) {
               $reflection = new ReflectionClass($className);
               if ($reflection->isSubclassOf(PaymentMethod::class) && ! $reflection->isAbstract()) {
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
         if ($paymentMethod->getId() === $this->order->details['payment']) {
            $this->paymentMethod = $paymentMethod;
            break;
         }
      }
   }
}
