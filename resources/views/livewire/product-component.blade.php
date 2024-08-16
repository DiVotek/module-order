<div class="wrapper">
   <div class="wrapper pt-0">
      <div class="container">
         <div class="row gap-y-12">
            <div class="col-xs-8 col-s-6 md:pr-3.5">
               <div class="aspect-1 flex justify-center items-center bg-base-200">
                  <x-core.image src="{{$product->image}}" alt="" width="144" heigth="144"
                     class="size-36 object-contain" />
               </div>
            </div>
            <div class="col-md-6 md:pl-3.5">
               <div class="divide-y divide-base-content/55">
                  <div class="pb-8">
                     <h1
                        class="text-xl xs:text-2xl lg:text-[2rem] uppercase text-base-content lg:max-w-[80%] mb-3 line-clamp-2">
                        {{$product->seo->heading ?? $product->name}}
                     </h1>
                     <div class="mb-6">
                        <div class="inline-flex items-center gap-x-2">
                           <svg class="size-3.5 text-main" viewBox="0 0 14 12" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M4.45589 12L4.37888 11.8763C3.20257 9.98619 0.0766667 5.97519 0.045092 5.9349L0 5.87713L1.06498 4.88458L4.43612 7.10449C6.55868 4.50702 8.53887 2.72296 9.83055 1.68114C11.2435 0.541491 12.1633 0.0168345 12.1726 0.0118069L12.1935 0H14L13.8274 0.144925C9.38947 3.8727 4.57917 11.7957 4.53125 11.8753L4.45589 12Z"
                                 fill="currentColor" />
                           </svg>
                           <span
                              class="text-base s:text-lg font-normal text-main">{{ _t('In stock') }}</span>
                        </div>
                        {{-- <div class="inline-flex items-center gap-x-2">
                                        <svg class="size-5 text-error"xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-base s:text-lg font-normal text-error">{{ _t('Out of stock') }}</span>
                     </div> --}}
                  </div>
                  <div class="max-xs:flex-col flex xs:items-end max-xs:gap-y-6 gap-x-9">
                     <div class="text-3xl lg:text-[2rem] text-base-content">
                        <span>{{$product->price}}</span>
                        <span>{{app('currency')->name}}</span>
                     </div>
                     <div class="flex items-center gap-x-1.5">
                        <span class="text-base text-base-content block">{{ _t('Count') }}</span>
                        <div class="relative">
                           <button
                              class="text-main-content size-7 flex justify-center items-center absolute top-1/2 left-2 -translate-y-1/2 bg-main transition-colors hover:bg-main/80 hover:disabled:bg-main disabled:opacity-65"
                              wire:disabled="$quantity < 0"
                              aria-label="{{ _t('Remove one item') }}" wire:click="changeQuantity({{$quantity - 1}})">
                              <svg class="size-auto" width="13" height="2" viewBox="0 0 13 2"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M0 1H13" stroke="currentColor" stroke-width="2" />
                              </svg>
                           </button>
                           <input type="number" wire:model="quantity" id="product_1" name="product-1">
                           <button
                              class="text-main-content size-7 flex justify-center items-center absolute top-1/2 right-2 -translate-y-1/2 bg-main transition-colors hover:bg-main/80 hover:disabled:bg-main disabled:opacity-65"
                              aria-label="{{ _t('Add one item') }}" wire:click="changeQuantity({{$quantity + 1}})">
                              <svg class="size-auto" width="12" height="12" viewBox="0 0 12 12"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M0 6H12" stroke="currentColor" stroke-width="2" />
                                 <path d="M6 12L6 1.19209e-07" stroke="currentColor"
                                    stroke-width="2" />
                              </svg>
                           </button>
                        </div>
                        <span class="text-base text-base-content block">{{ _t('pcs') }}.</span>
                     </div>
                  </div>
               </div>
               <div class="pt-4 pb-8">
                  <span class="text-base text-base-content mb-4 block">Асортимент по вазі, гр</span>
                  <div class="flex items-center gap-2">
                     <div class="radio-option ">
                        <input type="radio" id="product_option_1" name="product-option">
                        <label for="product_option_1">
                           300
                        </label>
                     </div>
                     <div class="radio-option">
                        <input type="radio" id="product_option_2" name="product-option">
                        <label for="product_option_2">
                           300-500
                        </label>
                     </div>
                     <div class="radio-option">
                        <input type="radio" id="product_option_3" name="product-option">
                        <label for="product_option_3">
                           500-700
                        </label>
                     </div>
                  </div>
               </div>
               <div class="pt-6">
                  <!-- <x-core.link href="#" title="Some title"
                     class="link link-light underline mb-9 flex">Показати оптові ціни</x-core.link> -->
                  <button class="btn btn-dark mb-4" aria-label="{{ _t('Add to cart') }}" wire:click="addToCart()">
                     <svg class="size-5 text-base-content lg:group-hover:text-accent-content transition duration-200"
                        viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                           d="M13.7812 7.21683C13.4188 7.21683 13.125 7.51067 13.125 7.87308C13.125 9.32048 11.9474 10.4981 10.5 10.4981C9.0526 10.4981 7.875 9.32048 7.875 7.87308C7.875 7.51067 7.58116 7.21683 7.21875 7.21683C6.85634 7.21683 6.5625 7.51067 6.5625 7.87308C6.5625 10.0443 8.32874 11.8106 10.5 11.8106C12.6713 11.8106 14.4375 10.0443 14.4375 7.87308C14.4375 7.51067 14.1437 7.21683 13.7812 7.21683Z"
                           fill="currentColor" />
                        <path
                           d="M19.1995 15.8932L17.6748 6.25552C17.5229 5.29262 16.7052 4.59375 15.7301 4.59375H14.3782C14.0643 2.73451 12.4472 1.3125 10.5 1.3125C8.55274 1.3125 6.93566 2.73451 6.62176 4.59375H5.26984C4.29476 4.59375 3.47701 5.29262 3.32513 6.2552L1.8005 15.8932C1.65053 16.8427 1.92258 17.8065 2.54711 18.5375C3.17132 19.2684 4.08071 19.6875 5.04201 19.6875H15.9579C16.9192 19.6875 17.8286 19.2684 18.4528 18.5375C19.0774 17.8065 19.3494 16.8427 19.1995 15.8932ZM10.5 2.625C11.7195 2.625 12.7383 3.46482 13.0318 4.59375H7.96818C8.2617 3.46482 9.28048 2.625 10.5 2.625ZM17.455 17.6848C17.0804 18.1235 16.5347 18.375 15.9579 18.375H5.04201C4.46523 18.375 3.91953 18.1235 3.54494 17.6848C3.17004 17.2461 3.00693 16.668 3.09698 16.0983L4.6216 6.45996C4.67223 6.13921 4.94492 5.90625 5.26984 5.90625H15.7301C16.055 5.90625 16.3277 6.13921 16.3783 6.46028L17.903 16.0983C17.993 16.668 17.8299 17.2461 17.455 17.6848Z"
                           fill="currentColor" />
                     </svg>
                     {{ _t('Add to cart') }}
                  </button>
                  <p class="text-base font-light text-base-content">Мінімальне замовлення 400 грн, також
                     замовник отримує дисконт картку ( кешбек 4% )</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
