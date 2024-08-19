<div class="wrapper">
	<div class="container">
		<h1 class="title !text-left mb-6">{{$page->seo->h1 ?? $page->name}}</h1>
		<form wire:submit.prevent="submit">
			<div class="row flex-wrap-reverse gap-y-7">
				<div class="col-m-7 m:pr-3.5">
					<div class="space-y-5 *:pt-6 *:px-8 *:pb-10 *:bg-base-200">
						<div>
							<span class="text-xl font-normal text-base-content mb-5 block">{{ _t('Personal data') }}</span>
							<div x-data="{ hidden: false }" class="flex flex-col gap-y-7">
								@foreach ($this->fields() as $field)
								@if($field->name != 'comment')
								<input class="input-form" {{$field->getAttributes()}}>
								<x-error :name="$field->getName()" />
								@else
								<button type="button" @click="hidden = !hidden" class="link link-dark self-start text-base font-normal"
									:class="hidden ? 'text-base-content underline' : ''" aria-label="{{ _t('Add comment') }}">{{ _t('Add
									comment') }}</button>
								<textarea x-show="hidden" x-cloak x-transition:enter.scale.80 x-transition:leave.scale.100
									class="textarea-form" name="checkout-comment" id="checkout_comment"
									placeholder="{{ _t('Enter comment') }}" rows="4" maxlength="1000"></textarea>
								@endif
								@endforeach
							</div>
						</div>
						<div>
							<span class="text-xl font-normal text-base-content mb-5 block">{{ _t('Delivery methods') }}</span>
							<div x-data="{ method: $wire.entangle('deliveryMethod') }">
								@foreach ($deliveryMethods as $method)
								<div class="radio-input mb-4">
									<input wire:model="deliveryMethod" type="radio" id="delivery_method_{{$method->id}}" name="checkout-delivery">
									<label for="delivery_method_{{$method->id}}">
										<div>
										</div>
										<span>{{ $method->name }}</span>
									</label>
								</div>
								@endforeach
							</div>
						</div>
						<div>
							<span class="text-xl font-normal text-base-content mb-8 block">{{ _t('Payment methods') }}</span>
							<div class="flex flex-wrap">
								@foreach ($paymentMethods as $method)
								<div class="radio-input mb-4">
									<input wire:model="paymentMethod" type="radio" id="payment_method_{{$method->id}}" name="checkout-payment">
									<label for="payment_method_{{$method->id}}">
										<div>
										</div>
										<span>{{ $method->name }}</span>
									</label>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="col-m-5 m:pl-3.5 relative">
					<div class="pt-6 px-6 pb-10 bg-base-200">
						<span class="text-xl font-bold text-base-content block mb-6">{{ _t('Products to order') }}</span>
						<div class="space-y-3 mb-6">
							@foreach ($products as $key => $product)
							<div class="flex max-xs:flex-col items-start gap-y-3 gap-x-5">
								<div class="size-28 flex justify-center items-center flex-shrink-0 bg-base-100">
									<x-image src="{{$product['image']}}" alt="{{$product['name']}}" width="64" heigth="64" class="size-16 object-contain" />
								</div>
								<div class="space-y-3">
									<div class="flex justify-between items-center gap-x-8">
										<div class="*:block">
											<span class="text-sm font-normal text-base-content/60">{{$product->category->name ?? ''}}</span>
											<span class="text-lg s:text-xl m:text-lg lg:text-xl font-medium text-base-content line-clamp-2">{{$product['name']}}</span>
										</div>
										<button aria-label="{{ _t('Remove product') }}" type="button" wire:click="removeFromCart({{$product['id']}})">
											<svg class="size-[1.125rem] text-base-content/60" viewBox="0 0 18 18" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path d="M3 16C3 17.105 3.89498 18 5.00002 18H13C14.105 18 15 17.105 15 16V3.99998H3V16Z"
													fill="currentColor" />
												<path d="M12.5 0.999984L11.5 0H6.5L5.49997 0.999984H2V3H16V0.999984H12.5Z"
													fill="currentColor" />
											</svg>
										</button>
									</div>
									<div class="inline-block relative">
										<button
											class="text-main-content size-7 flex justify-center items-center absolute top-1/2 left-2 -translate-y-1/2 bg-main transition-colors hover:bg-main/80 hover:disabled:bg-main disabled:opacity-65"
											aria-label="{{ _t('Remove one item') }}" wire:click="changeQuantity({{$key}}, {{$product['quantity'] - 1}})">
											<svg class="size-auto" width="13" height="2" viewBox="0 0 13 2" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path d="M0 1H13" stroke="currentColor" stroke-width="2" />
											</svg>
										</button>
										<input type="number" :value="{{$product['quantity']}}" id="product_1" name="product-1" wire:change="changeQuantity({{$key}},$event.target.value)">
										<button
											class="text-main-content size-7 flex justify-center items-center absolute top-1/2 right-2 -translate-y-1/2 bg-main transition-colors hover:bg-main/80 hover:disabled:bg-main disabled:opacity-65"
											aria-label="{{ _t('Add one item') }}" wire:click="changeQuantity({{$key}}, {{$product['quantity'] + 1}})">
											<svg class="size-auto" width="12" height="12" viewBox="0 0 12 12" fill="none"
												xmlns="http://www.w3.org/2000/svg">
												<path d="M0 6H12" stroke="currentColor" stroke-width="2" />
												<path d="M6 12L6 1.19209e-07" stroke="currentColor" stroke-width="2" />
											</svg>
										</button>
									</div>
								</div>
							</div>
							@endforeach
						</div>
						<div class="space-y-5 mb-10 *:text-lg *:text-base-content *:flex *:justify-between *:items-center *:gap-y-2 *:gap-x-4">
							<div>
								<span class="font-base">{{ _t("Delivery") }}</span>
								<span class="font-medium">{{ _t("Free") }}</span>
							</div>
							<div>
								<span class="font-base">{{ _t("Comission") }}</span>
								<span class="font-medium">{{ _t("Free") }}</span>
							</div>
							<div>
								<span class="font-base">{{ _t("Total amount") }}</span>
								<span class="font-medium">{{$this->total()}} {{app('currency')->name}}</span>
							</div>
							<div>
								<span class="font-base">{{ _t("Cashback") }}</span>
								<span class="font-medium">600 грн</span>
							</div>
						</div>
						<div class="flex justify-center items-center">
							<button type="submit" class="btn btn-light text-base font-bold" aria-label="{{ _t('Make an order') }}">{{ _t('Make an order') }}</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
