<div class="space-y-5">
    <x-admin.loading-bar />

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat.card label="Total Visitors" :value="number_format($summary['total_visitors'])" hint="All saved visitor profiles" tone="slate" />
        <x-admin.stat.card label="Today" :value="number_format($summary['today_registrations'])" hint="Registrations received today" tone="blue" />
        <x-admin.stat.card label="Membership" :value="number_format($summary['membership_interest'])" hint="Interested in membership" tone="emerald" />
        <x-admin.stat.card label="Prayer Requests" :value="number_format($summary['prayer_requests'])" hint="Submitted care requests" tone="violet" />
    </div>

    <div class="grid gap-5 xl:grid-cols-12">
        <x-admin.card class="p-5 xl:col-span-8">
            <x-admin.section-header title="Visitor Trend" description="Registration movement over the last 12 months." />
            <div id="monthly-visitors-chart" class="mt-4 min-h-72"></div>
        </x-admin.card>

        <x-admin.card class="p-5 xl:col-span-4">
            <x-admin.section-header title="Profile Split" description="Gender distribution from submitted profiles." />
            <div id="gender-distribution-chart" class="mt-4 min-h-72"></div>
        </x-admin.card>
    </div>

    <div class="grid gap-5 xl:grid-cols-12">
        <x-admin.card class="p-5 xl:col-span-7">
            <x-admin.section-header title="Recent Registrations" description="Latest visitors from the QR form.">
                <x-slot:actions>
                    <a href="{{ route('admin.visitors.index') }}" class="text-sm font-semibold text-[#f5b82e] hover:text-[#f8d884]">Open directory</a>
                </x-slot:actions>
            </x-admin.section-header>

            <div class="mt-4 divide-y divide-white/10">
                @forelse ($recentRegistrations->take(6) as $registration)
                    <a href="{{ route('admin.visitors.show', $registration->visitor) }}" class="flex items-center justify-between gap-4 py-3 transition hover:bg-white/[0.03] sm:px-2">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-white">{{ $registration->visitor->full_name }}</p>
                            <p class="mt-1 truncate text-xs text-slate-400">{{ $registration->churchService->name }} · {{ $registration->created_at->format('M j, g:i A') }}</p>
                        </div>
                        <x-admin.badge tone="blue">{{ $registration->qrCode->label }}</x-admin.badge>
                    </a>
                @empty
                    <x-admin.empty-state title="No registrations" description="Visitors submitted from QR codes will appear here." />
                @endforelse
            </div>
        </x-admin.card>

        <x-admin.card class="p-5 xl:col-span-5">
            <x-admin.section-header title="Prayer & Care" description="Recent pastoral care signals." />

            <div class="mt-4 space-y-3">
                @forelse ($prayerRequests->take(4) as $request)
                    <div class="rounded-xl border border-white/10 bg-white/[0.035] p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-semibold text-white">{{ $request->visitor->full_name }}</p>
                            <span class="shrink-0 text-xs text-slate-500">{{ $request->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-300">{{ $request->prayer_request }}</p>
                    </div>
                @empty
                    <x-admin.empty-state title="No prayer requests" description="Prayer requests submitted during registration will appear here." />
                @endforelse
            </div>
        </x-admin.card>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const charts = @json($charts);
            const palette = ['#60a5fa', '#f5b82e', '#34d399', '#a78bfa', '#fb7185', '#94a3b8'];
            const textColor = '#94a3b8';
            const gridColor = 'rgba(255,255,255,0.08)';

            const seriesFrom = (items) => items.map((item) => item.value);
            const labelsFrom = (items) => items.map((item) => item.label);

            const renderWhenReady = () => {
                if (!window.ApexCharts) {
                    setTimeout(renderWhenReady, 100);
                    return;
                }

                new ApexCharts(document.querySelector('#monthly-visitors-chart'), {
                    chart: { type: 'area', height: 288, toolbar: { show: false }, foreColor: textColor, fontFamily: 'Montserrat, ui-sans-serif, system-ui', background: 'transparent' },
                    colors: ['#f5b82e'],
                    series: [{ name: 'Visitors', data: seriesFrom(charts.monthlyVisitors) }],
                    xaxis: { categories: labelsFrom(charts.monthlyVisitors), labels: { style: { colors: textColor } }, axisBorder: { color: gridColor }, axisTicks: { color: gridColor } },
                    yaxis: { labels: { style: { colors: textColor } } },
                    stroke: { curve: 'smooth', width: 3 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.22, opacityTo: 0.02 } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: gridColor },
                    tooltip: { theme: 'dark' }
                }).render();

                new ApexCharts(document.querySelector('#gender-distribution-chart'), {
                    chart: { type: 'donut', height: 288, foreColor: textColor, fontFamily: 'Montserrat, ui-sans-serif, system-ui', background: 'transparent' },
                    labels: labelsFrom(charts.genderDistribution),
                    series: seriesFrom(charts.genderDistribution),
                    colors: palette,
                    legend: { position: 'bottom', labels: { colors: textColor } },
                    stroke: { colors: ['#0b1020'] },
                    dataLabels: { enabled: false },
                    tooltip: { theme: 'dark' }
                }).render();
            };

            renderWhenReady();
        });
    </script>
</div>
