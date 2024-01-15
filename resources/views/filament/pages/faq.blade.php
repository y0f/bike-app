<x-filament-panels::page>

    <div class="p-4">
        <h2 class="text-3xl font-bold mb-8 p-4">Veelgestelde vragen</h2>

        @foreach ($faqTitles as $index => $title)
            <div x-data="{ openFaq: @entangle('openFaq') }">
                <div @click="openFaq === {{ $index }} ? openFaq = null : openFaq = {{ $index }}"
                    class="cursor-pointer flex justify-between items-center p-4">
                    <h3 class="text-xl font-semibold">{{ $title }}</h3>
                    <span x-show="openFaq === {{ $index }}">
                        <x-heroicon-o-minus class="h-5 w-5 text-primary-600" />
                    </span>
                    <span x-show="openFaq !== {{ $index }}">
                        <x-heroicon-o-plus class="h-5 w-5 text-primary-600" />
                    </span>
                </div>
                <div x-show="openFaq === {{ $index }}" x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-400" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="faq-content text-gray-500 p-4">
                    {{ $faqContent[$index] }}
                </div>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>

