<div>
   <div x-data="{ open: false }" class="wrapper">
      <div class="container">
         <div class="flex justify-between items-center gap-4 flex-wrap mb-8">
            <h1 class="title !text-left">Наша продукція</h1>
            <button @click="open = true; document.querySelector('body').classList.add('block')"
               class="btn btn-dark lg:hidden" aria-label="{{ _t('Open filter') }}">
               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                     d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
               </svg>
               {{ _t('Filter') }}
            </button>
         </div>
         <div class="grid lg:grid-cols-4 gap-y-6 gap-x-8 items-start">
            <div class="max-lg:hidden">
               <fieldset class="pt-6 pl-5 pb-7 pr-3 bg-base-200 divide-y divide-base-content"
                  aria-label="{{ _t('Filter') }}">
                  <div class="space-y-8 pb-6">
                     <div class="checkbox-input">
                        <input type="checkbox" id="filter_1" name="filter-1" aria-label="">
                        <label for="filter_1">
                           <div>
                              <svg class="size-3" viewBox="0 0 12 11" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path
                                    d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                    fill="currentColor" />
                              </svg>
                           </div>
                           <span>В'ялена риба</span>
                        </label>
                     </div>
                     <div class="checkbox-input">
                        <input type="checkbox" id="filter_2" name="filter-2" aria-label="">
                        <label for="filter_2">
                           <div>
                              <svg class="size-3" viewBox="0 0 12 11" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path
                                    d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                    fill="currentColor" />
                              </svg>
                           </div>
                           <span>Гаряче копчення</span>
                        </label>
                     </div>
                     <div class="checkbox-input">
                        <input type="checkbox" id="filter_3" name="filter-3" aria-label="">
                        <label for="filter_3">
                           <div>
                              <svg class="size-3" viewBox="0 0 12 11" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path
                                    d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                    fill="currentColor" />
                              </svg>
                           </div>
                           <span>Солона риба</span>
                        </label>
                     </div>
                     <div class="checkbox-input">
                        <input type="checkbox" id="filter_4" name="filter-4" aria-label="">
                        <label for="filter_4">
                           <div>
                              <svg class="size-3" viewBox="0 0 12 11" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path
                                    d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                    fill="currentColor" />
                              </svg>
                           </div>
                           <span>Пресерви</span>
                        </label>
                     </div>
                     <div class="checkbox-input">
                        <input type="checkbox" id="filter_5" name="filter-5" aria-label="">
                        <label for="filter_5">
                           <div>
                              <svg class="size-3" viewBox="0 0 12 11" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                 <path
                                    d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                    fill="currentColor" />
                              </svg>
                           </div>
                           <span>Холодне копчення</span>
                        </label>
                     </div>
                  </div>
                  <div class="pt-6">
                     <span class="block mb-4 text-base-content">Ціна, грн</span>
                     <div class="double-range">
                        <div class="text-input">
                           <input type="number" name="min" value="0" />
                           <div class="separator"></div>
                           <input type="number" name="max" value="70" />
                        </div>
                        <div class="range-slider">
                           <span class="range-fill"></span>
                        </div>
                        <div class="range-input">
                           <input type="range" class="min" min="0" max="100" value="0"
                              step="1" />
                           <input type="range" class="max" min="0" max="100" value="70"
                              step="1" />
                        </div>
                     </div>
                  </div>
               </fieldset>
            </div>
            <div class="lg:col-span-3 grid s:grid-cols-2 md:grid-cols-3 gap-y-7 gap-x-8">
               @include('components.modules.category.product-card')
               @include('components.modules.category.product-card')
               @include('components.modules.category.product-card')
            </div>
         </div>
      </div>
      <div x-show="open" x-cloak class="w-full h-dvh absolute top-0 right-0 z-50 m:hidden">
         <div x-show="open" x-cloak class="absolute inset-0 z-0 backdrop-blur-sm bg-base-200/80"
            x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="duration-200 ease-in delay-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
         <fieldset @click.outside="open = false; document.querySelector('body').classList.remove('block')"
            x-show="open" x-cloak
            class="h-full w-full max-w-[85%] absolute top-0 right-0 z-10 px-5 py-12 bg-base-200 divide-y divide-base-content shadow-[-1px_0px_0px_0px] shadow-main"
            aria-label="{{ _t('Filter') }}" x-transition:enter="duration-300 ease-out delay-200"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-0 opacity-100"
            x-transition:leave="duration-150 ease-in" x-transition:leave-start="translate-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0">
            <div class="flex justify-between items-center gap-x-4 pb-8">
               <span class="text-xl font-medium text-base-content inline-flex items-center gap-x-2">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor" class="size-6">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                  </svg>
                  {{ _t('Filter') }}
               </span>
               <button @click="open = false; document.querySelector('body').classList.remove('block')"
                  class="text-main size-6">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                  </svg>
               </button>
            </div>
            <div class="pt-8 divide-y divide-base-content">
               <div class="space-y-8 pb-6">
                  <div class="checkbox-input">
                     <input type="checkbox" id="filter_1" name="filter-1" aria-label="">
                     <label for="filter_1">
                        <div>
                           <svg class="size-3" viewBox="0 0 12 11" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                 fill="currentColor" />
                           </svg>
                        </div>
                        <span>В'ялена риба</span>
                     </label>
                  </div>
                  <div class="checkbox-input">
                     <input type="checkbox" id="filter_2" name="filter-2" aria-label="">
                     <label for="filter_2">
                        <div>
                           <svg class="size-3" viewBox="0 0 12 11" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                 fill="currentColor" />
                           </svg>
                        </div>
                        <span>Гаряче копчення</span>
                     </label>
                  </div>
                  <div class="checkbox-input">
                     <input type="checkbox" id="filter_3" name="filter-3" aria-label="">
                     <label for="filter_3">
                        <div>
                           <svg class="size-3" viewBox="0 0 12 11" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                 fill="currentColor" />
                           </svg>
                        </div>
                        <span>Солона риба</span>
                     </label>
                  </div>
                  <div class="checkbox-input">
                     <input type="checkbox" id="filter_4" name="filter-4" aria-label="">
                     <label for="filter_4">
                        <div>
                           <svg class="size-3" viewBox="0 0 12 11" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                 fill="currentColor" />
                           </svg>
                        </div>
                        <span>Пресерви</span>
                     </label>
                  </div>
                  <div class="checkbox-input">
                     <input type="checkbox" id="filter_5" name="filter-5" aria-label="">
                     <label for="filter_5">
                        <div>
                           <svg class="size-3" viewBox="0 0 12 11" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                              <path
                                 d="M3.81934 11L3.75333 10.8866C2.74506 9.15401 0.0657143 5.47725 0.0386502 5.44032L0 5.38737L0.912838 4.47753L3.80238 6.51245C5.62172 4.13144 7.31904 2.49605 8.42619 1.54105C9.63732 0.496367 10.4257 0.0154316 10.4336 0.010823L10.4516 0H12L11.8521 0.132848C8.04811 3.54997 3.925 10.8127 3.88393 10.8857L3.81934 11Z"
                                 fill="currentColor" />
                           </svg>
                        </div>
                        <span>Холодне копчення</span>
                     </label>
                  </div>
               </div>
               <div class="pt-6">
                  <span class="block mb-4 text-base-content">Ціна, грн</span>
                  <div class="double-range">
                     <div class="text-input justify-start">
                        <input type="number" name="min" value="0" />
                        <div class="separator"></div>
                        <input type="number" name="max" value="70" />
                     </div>
                  </div>
               </div>
            </div>
         </fieldset>
      </div>
   </div>
</div>
