<div class="space-y-6">
    <x-admin.loading-bar />

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat.card label="Total Visitors" :value="number_format($summary['total_visitors'])" hint="All captured visitor profiles" tone="slate" />
        <x-admin.stat.card label="Today" :value="number_format($summary['today_registrations'])" hint="Registrations received today" tone="blue" />
        <x-admin.stat.card label="Membership Interest" :value="number_format($summary['membership_interest'])" hint="Visitors requesting membership" tone="emerald" />
        <x-admin.stat.card label="Prayer Requests" :value="number_format($summary['prayer_requests'])" hint="Open pastoral care signals" tone="violet" />
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.card class="p-5 xl:col-span-2">
            <x-admin.section-header title="Monthly Visitors" description="Registration trend over the last 12 months." />
            <div id="monthly-visitors-chart" class="mt-5 min-h-80"></div>
        </x-admin.card>

        <x-admin.card class="p-5">
            <x-admin.section-header title="Gender Distribution" description="Visitor profile split." />
            <div id="gender-distribution-chart" class="mt-5 min-h-80"></div>
        </x-admin.card>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.card class="p-5">
            <x-admin.section-header title="Weekly Visitors" description="Last 8 weeks." />
            <div id="weekly-visitors-chart" class="mt-5 min-h-72"></div>
        </x-admin.card>

        <x-admin.card class="p-5">
            <x-admin.section-header title="Age Distribution" description="Age buckets from date of birth." />
            <div id="age-distribution-chart" class="mt-5 min-h-72"></div>
        </x-admin.card>

        <x-admin.card class="p-5">
            <x-admin.section-header title="Recent Activity" description="Latest registration events." />
            <div class="mt-5 space-y-4">
                @forelse ($recentActivity as $activity)
                    <div class="flex gap-3">
                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-slate-950"></div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-950">{{ $activity['title'] }}</p>
                            <p class="text-sm text-slate-500">{{ $activity['description'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ $activity['timestamp']->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <x-admin.empty-state title="No activity yet" description="New registrations will appear here." />
                @endforelse
            </div>
        </x-admin.card>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <x-admin.card class="p-5">
            <x-admin.section-header title="Recent Registrations" description="Latest visitors captured from QR forms.">
                <x-slot:actions>
                    <a href="{{ route('admin.visitors.index') }}" class="text-sm font-semibold text-slate-950 hover:text-slate-600">View all</a>
                </x-slot:actions>
            </x-admin.section-header>

            <div class="mt-5 space-y-3">
                @forelse ($recentRegistrations as $registration)
                    <a href="{{ route('admin.visitors.show', $registration->visitor) }}" class="flex items-center justify-between gap-4 rounded-2xl border border-slate-100 p-4 transition hover:border-slate-300 hover:bg-slate-50">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-950">{{ $registration->visitor->full_name }}</p>
                            <p class="mt-1 truncate text-sm text-slate-500">{{ $registration->churchService->name }} · {{ $registration->created_at->format('M j, g:i A') }}</p>
                        </div>
                        <x-admin.badge tone="blue">{{ $registration->qrCode->label }}</x-admin.badge>
                    </a>
                @empty
                    <x-admin.empty-state title="No registrations" description="Visitors submitted from QR codes will appear here." />
                @endforelse
            </div>
        </x-admin.card>

        <x-admin.card class="p-5">
            <x-admin.section-header title="Prayer Requests" description="Recent prayer and care requests." />

            <div class="mt-5 space-y-3">
                @forelse ($prayerRequests as $request)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-slate-950">{{ $request->visitor->full_name }}</p>
                            <span class="text-xs text-slate-400">{{ $request->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $request->prayer_request }}</p>
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
            const palette = ['#162b75', '#d29a18', '#d9232e', '#64748b', '#10b981', '#8b5cf6'];

            const seriesFrom = (items) => items.map((item) => item.value);
            const labelsFrom = (items) => items.map((item) => item.label);

            const renderWhenReady = () => {
                if (!window.ApexCharts) {
                    setTimeout(renderWhenReady, 100);
                    return;
                }

                new ApexCharts(document.querySelector('#monthly-visitors-chart'), {
                    chart: { type: 'area', height: 320, toolbar: { show: false }, fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    colors: ['#162b75'],
                    series: [{ name: 'Visitors', data: seriesFrom(charts.monthlyVisitors) }],
                    xaxis: { categories: labelsFrom(charts.monthlyVisitors), labels: { style: { colors: '#64748b' } } },
                    yaxis: { labels: { style: { colors: '#64748b' } } },
                    stroke: { curve: 'smooth', width: 3 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.25, opacityTo: 0.02 } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#e2e8f0' }
                }).render();

                new ApexCharts(document.querySelector('#gender-distribution-chart'), {
                    chart: { type: 'donut', height: 320, fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    labels: labelsFrom(charts.genderDistribution),
                    series: seriesFrom(charts.genderDistribution),
                    colors: palette,
                    legend: { position: 'bottom' },
                    dataLabels: { enabled: false }
                }).render();

                new ApexCharts(document.querySelector('#weekly-visitors-chart'), {
                    chart: { type: 'bar', height: 288, toolbar: { show: false }, fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    colors: ['#162b75'],
                    series: [{ name: 'Visitors', data: seriesFrom(charts.weeklyVisitors) }],
                    xaxis: { categories: labelsFrom(charts.weeklyVisitors) },
                    plotOptions: { bar: { borderRadius: 8, columnWidth: '48%' } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#e2e8f0' }
                }).render();

                new ApexCharts(document.querySelector('#age-distribution-chart'), {
                    chart: { type: 'bar', height: 288, toolbar: { show: false }, fontFamily: 'Montserrat, ui-sans-serif, system-ui' },
                    colors: ['#d29a18'],
                    series: [{ name: 'Visitors', data: seriesFrom(charts.ageDistribution) }],
                    xaxis: { categories: labelsFrom(charts.ageDistribution) },
                    plotOptions: { bar: { borderRadius: 8, horizontal: true } },
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#e2e8f0' }
                }).render();
            };

            renderWhenReady();
        });
    </script>
</div>
