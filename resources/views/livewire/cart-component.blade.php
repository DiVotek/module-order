<div x-data="{dropdownOpen: $wire.entangle('open')}" x-show="dropdownOpen"
   @click.away="$wire.close()"
   x-transition:enter="ease-out duration-200"
   x-transition:enter-start="-translate-y-2"
   x-transition:enter-end="translate-y-0"
   class="fixed top-[18%] z-50 w-80 right-0"
   x-cloak>
   <div class=" px-3 py-6  bg-base-200 space-y-5 transition-all duration-200 shadow-[-1px_0px_8px_0px_#00000024] shadow-transparent">
      <div class="flex flex-col">
         @foreach ($cart->products as $product)
         <div class="flex items-center justify-around flex-wrap w-full border-y-2 border-white/40 p-3">
            <div class="h-12 w-12">
               <x-core.image :src="$product['image']" />
            </div>
            <div class="flex flex-col">
               <div>{{$product['name']}}</div>
               <div>{{$product['price']}} {{app('currency')->name}} </div>
            </div>
            <div class="flex items-center">
               <x-core.button class="btn btn-dark border-0" wire:click="removeFromCart({{$product['id']}})">
               <svg class="size-5 text-base-content lg:group-hover:text-accent-content transition duration-200"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>
               </x-core.button>
            </div>
         </div>
         @endforeach
         @if (count($cart->products) <= 0)
            <div class="flex items center justify-center">
            {{_t("No products")}}
      </div>
      @else
      <div class="text-lg text-end mt-4">
         {{_t("Total")}}: {{$cart->total}} {{app('currency')->name}}
      </div>
      <x-core.button class="btn btn-dark h-[3.25rem] font-medium normal-case mt-4" wire:click="checkout">{{_t("Checkout")}}</x-core.button>
      @endif
   </div>
</div>
