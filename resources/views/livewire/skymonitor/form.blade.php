<div class="space-y-6">

    <x-text-input wire:model.fill="form.user_id" id="user_id" name="user_id" type="hidden" value="{{ auth()->id() }}"/>

    <div>
        <x-input-label for="city" :value="__('City')"/>
        <x-text-input wire:model="form.city" id="city" name="city" type="text" class="mt-1 block w-full" autocomplete="city" placeholder="City"/>
        @error('form.city')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="email" :value="__('Email')"/>
        <x-text-input wire:model="form.email" id="email" name="email" type="text" class="mt-1 block w-full" autocomplete="email" placeholder="Email"/>
        @error('form.email')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="phone" :value="__('Phone')"/>
        <x-text-input wire:model="form.phone" id="phone" name="phone" type="text" class="mt-1 block w-full" autocomplete="phone" placeholder="Phone"/>
        @error('form.phone')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="uv_index_threshold" :value="__('Uv Index Threshold')"/>
        <x-text-input wire:model="form.uv_index_threshold" id="uv_index_threshold" name="uv_index_threshold" type="text" class="mt-1 block w-full" autocomplete="uv_index_threshold" placeholder="Uv Index Threshold"/>
        @error('form.uv_index_threshold')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="precipitation_threshold" :value="__('Precipitation Threshold')"/>
        <x-text-input wire:model="form.precipitation_threshold" id="precipitation_threshold" name="precipitation_threshold" type="text" class="mt-1 block w-full" autocomplete="precipitation_threshold" placeholder="Precipitation Threshold"/>
        @error('form.precipitation_threshold')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>Submit</x-primary-button>
    </div>
</div>
