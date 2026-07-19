<div class="space-y-5">
    <x-admin.loading-bar />

    <x-admin.card class="p-5">
        <div class="grid gap-4 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-3">
                <label class="text-sm font-semibold text-slate-300">Start date</label>
                <input wire:model.lazy="start" type="date" class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-sm text-white outline-none transition focus:border-[#f5b82e] focus:ring-4 focus:ring-[#f5b82e]/10" />
            </div>
            <div class="lg:col-span-3">
                <label class="text-sm font-semibold text-slate-300">End date</label>
                <input wire:model.lazy="end" type="date" class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-sm text-white outline-none transition focus:border-[#f5b82e] focus:ring-4 focus:ring-[#f5b82e]/10" />
            </div>
            <div class="lg:col-span-3">
                <label class="text-sm font-semibold text-slate-300">Group by</label>
                <select wire:model="period" class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-2.5 text-sm text-white outline-none transition focus:border-[#f5b82e] focus:ring-4 focus:ring-[#f5b82e]/10">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            <div class="lg:col-span-3">
                <button wire:click="load" class="w-full rounded-xl bg-[#f5b82e] px-4 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-[#f8d884]">Refresh report</button>
            </div>
        </div>
    </x-admin.card>

    <div class="grid gap-3 md:grid-cols-3">
        <x-admin.stat.card label="Registrations" :value="number_format(array_sum($data['visitors'] ?? []))" hint="Within selected period" tone="blue" />
        <x-admin.stat.card label="Returning" :value="number_format($data['returning']['returning_visitors'] ?? 0)" hint="Visitors with more than one visit" tone="emerald" />
        <x-admin.stat.card label="Follow-up Rate" :value="($data['followups']['completion_rate'] ?? 0).'%'" hint="Completed follow-up actions" tone="violet" />
    </div>

    <div class="grid gap-5 xl:grid-cols-12">
        <x-admin.card class="p-5 xl:col-span-8">
            <x-admin.section-header title="Registrations Over Time" description="Visitor volume for the selected date range." />
            <div id="chart-visitors" class="mt-4 min-h-72"></div>
        </x-admin.card>

        <x-admin.card class="p-5 xl:col-span-4">
            <x-admin.section-header title="Gender" description="Profile distribution." />
            <div id="chart-gender" class="mt-4 min-h-72"></div>
        </x-admin.card>
    </div>

    <x-admin.card class="p-5">
        <x-admin.section-header title="Age Distribution" description="Age groups from visitor profile data." />
        <div id="chart-age" class="mt-4 min-h-72"></div>
    </x-admin.card>

    <div id="reports-data"
         data-visitors='@json(array_values($data['visitors'] ?? []))'
         data-visitors-labels='@json(array_keys($data['visitors'] ?? []))'
         data-gender='@json(array_values($data['gender'] ?? []))'
         data-gender-labels='@json(array_keys($data['gender'] ?? []))'
         data-age='@json(array_values($data['age'] ?? []))'
         data-age-labels='@json(array_keys($data['age'] ?? []))'
    ></div>

    <script>
        document.addEventListener('livewire:init', function () {
            const textColor = '#94a3b8';
            const gridColor = 'rgba(255,255,255,0.08)';
            const palette = ['#60a5fa', '#f5b82e', '#34d399', '#a78bfa', '#fb7185', '#94a3b8'];
            let renderedCharts = [];

            function destroyCharts() {
                renderedCharts.forEach((chart) => chart.destroy());
                renderedCharts = [];
            }

            function drawCharts() {
                const el = document.getElementById('reports-data');
                if (!el || !window.ApexCharts) return;

                destroyCharts();

                const visitors = JSON.parse(el.dataset.visitors || '[]');
                const labels = JSON.parse(el.dataset.visitorsLabels || '[]');
                const genderSeries = JSON.parse(el.dataset.gender || '[]');
                const genderLabels = JSON.parse(el.dataset.genderLabels || '[]');
                const ageSeries = JSON.parse(el.dataset.age || '[]');
                const ageLabels = JSON.parse(el.dataset.ageLabels || '[]');

                renderedCharts.push(new ApexCharts(document.querySelector('#chart-visitors'), {
                    chart: { type: 'area', height: 288, toolbar: { show: false }, foreColor: textColor, background: 'transparent', fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    colors: ['#f5b82e'],
                    series: [{ name: 'Visitors', data: visitors }],
                    xaxis: { categories: labels },
                    stroke: { curve: 'smooth', width: 3 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.22, opacityTo: 0.02 } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: gridColor },
                    tooltip: { theme: 'dark' }
                }));

                renderedCharts.push(new ApexCharts(document.querySelector('#chart-gender'), {
                    chart: { type: 'donut', height: 288, foreColor: textColor, background: 'transparent', fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    colors: palette,
                    series: genderSeries,
                    labels: genderLabels,
                    legend: { position: 'bottom', labels: { colors: textColor } },
                    stroke: { colors: ['#0b1020'] },
                    dataLabels: { enabled: false },
                    tooltip: { theme: 'dark' }
                }));

                renderedCharts.push(new ApexCharts(document.querySelector('#chart-age'), {
                    chart: { type: 'bar', height: 288, toolbar: { show: false }, foreColor: textColor, background: 'transparent', fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    colors: ['#60a5fa'],
                    series: [{ name: 'Visitors', data: ageSeries }],
                    xaxis: { categories: ageLabels },
                    plotOptions: { bar: { borderRadius: 8, columnWidth: '42%' } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: gridColor },
                    tooltip: { theme: 'dark' }
                }));

                renderedCharts.forEach((chart) => chart.render());
            }

            drawCharts();
            Livewire.hook('morph.updated', drawCharts);
        });
    </script>
</div>
