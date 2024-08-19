<section>
   <div class="relative py-6 2xl:pb-44 bg-base-100 rounded-b-9xl overflow-hidden">
      <div class="relative container px-4 mx-auto z-10">
         <h2 class="mb-5 xl:mb-10 text-9xl xl:text-10xl leading-normal font-heading font-medium text-center">{{$page->seo->heading ?? $page->name()}}</h2>
         <p class="mb-14 xl:mb-20 text-lg text-darkBlueGray-400 font-heading text-center">{{$page->seo->summary ?? ''}}</p>
         <div class="mx-auto max-w-2xl">
            <h3 class="mb-6 text-xl font-heading font-medium">{{_t("What you ordered")}}:</h3>
            @foreach ($order->products as $product)
            <div class="sm:flex sm:items-center p-10 xl:py-5 xl:px-12 mb-3 rounded-3xl border-accent border rounded-none">
               <x-link href="{{$product['slug']}}" title="{{$product['name']}}">
                  <x-image src="{{$product['image']}}" alt="" width="64" height="64" class="h-16 mb-6 sm:mb-0 sm:mr-12 mx-auto sm:ml-0 object-cover" />
               </x-link>
               <div>
               <x-link href="{{$product['slug']}}" title="{{$product['name']}}" class="inline-block mb-1 text-lg hover:underline font-heading font-medium color-bg-base-content text-base-content">
                  {{$product['name']}}
               </x-link>
                  <div class="flex flex-wrap">
                     <p class="text-sm font-medium">
                        <span>{{_t("Quantity")}}:</span>
                        <span class="ml-2 text-gray-400">{{$product['quantity']}}</span>
                     </p>
                  </div>
               </div>
            </div>
            @endforeach
            <div class="sm:max-w-max sm:ml-auto">
               <p class="flex items-center justify-between font-heading font-medium">
                  <span class="mr-4">{{_t("Total")}}</span>
                  <span class="flex items-center">
                     <span class="text-3xl text-blue-500">{{$order->total}}</span>
                     <span class="mr-2 text-sm text-blue-500">{{$order->currency}}</span>
                  </span>
               </p>
            </div>
         </div>
      </div>
   </div>
</section>
