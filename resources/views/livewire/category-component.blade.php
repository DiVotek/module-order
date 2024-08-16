<div class="wrapper">
   <h1 class="container title !text-left pb-5">{{$category->seo->heading ?? $category->name}}</h1>
   <div
      class="pt-6 pb-11 mb-10 md:mb-12 lg:mb-20 relative bg-base-200 after:size-12 max-lg:after:hidden after:block after:absolute after:bottom-0 after:left-1/2 after:translate-y-1/2 after:-translate-x-1/2 after:rotate-45 after:bg-base-200">
      <div class="container overflow-x-auto">
         <div class="flex justify-center gap-x-8 items-center">
            @foreach ($categories as $cat)
            <x-core.link href="{{$cat->route()}}" title="{{$cat->name}}"
               class="flex flex-col justify-center items-center gap-y-6 group @if($category->id == $cat->id) active @endif">
               <div class="size-24 s:size-28 lg:size-36">
                  <x-core.image src="{{$cat->image}}" alt="{{$cat->name}}" width="144" heigth="144"
                     class="size-full object-contain transition-all duration-200 group-hover:scale-105" />
               </div>
               <h3
                  class="text-xl s:text-2xl line-clamp-2 h-[2.666em] text-white text-center duration-300 ease-linear group-hover:text-accent @if($category->id == $cat->id) !text-accent @endif">
                  {{$cat->name}}
               </h3>
            </x-core.link>
            @endforeach
         </div>
      </div>
   </div>
   <div class="container flex flex-col justify-center items-center gap-y-12">
      <h2 class="title">{{_t("Products")}}</h2>
      <div class="w-full grid s:grid-cols-2 lg:grid-cols-3 gap-y-12 gap-x-7">
         @foreach ($category->products as $product)
         <x-core.link href="{{$product->route()}}" title="{{$product->name}}" class="block space-y-7 group">
            <div class="aspect-w-3 aspect-h-2 relative overflow-hidden">
               <x-core.image src="{{$product->image}}" alt="{{$product->name}}" width="100%" height="100%" class="size-full object-contain transition-transform duration-300 group-hover:scale-110" />
            </div>
            <span class="text-xl xs:text-2xl s:text-xl md:text-2xl lg:text-xl xl:text-2xl font-bold text-base-content text-center h-[2.8em] block line-clamp-2 transition-colors group-hover:text-accent">{{$product->name}}</span>
         </x-core.link>
         @endforeach
      </div>
   </div>
</div>
