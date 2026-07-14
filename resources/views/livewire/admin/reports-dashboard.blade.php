<div>
    <div class="flex items-center gap-3 mb-4">
        <div>
            <label class="block text-sm">Start</label>
            <input wire:model.lazy="start" type="date" class="border rounded px-2 py-1" />
        </div>
        <div>
            <label class="block text-sm">End</label>
            <input wire:model.lazy="end" type="date" class="border rounded px-2 py-1" />
        </div>
        <div>
            <label class="block text-sm">Period</label>
            <select wire:model="period" class="border rounded px-2 py-1">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>
        <div class="flex items-end">
            <button wire:click="load" class="bg-blue-600 text-white px-3 py-1 rounded">Refresh</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-4">
            <h3 class="text-sm text-gray-500">Total Registrations</h3>
            <div class="text-2xl font-bold">{{ array_sum($data['visitors'] ?? []) }}</div>
        </div>
        <div class="card p-4">
            <h3 class="text-sm text-gray-500">Returning Visitors</h3>
            <div class="text-2xl font-bold">{{ $data['returning']['returning_visitors'] ?? 0 }}</div>
        </div>
        <div class="card p-4">
            <h3 class="text-sm text-gray-500">Follow-up Completion</h3>
            <div class="text-2xl font-bold">{{ $data['followups']['completion_rate'] ?? 0 }}%</div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-medium mb-2">Visitors Over Time</h3>
        <div id="chart-visitors" style="height:320px"></div>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h4 class="mb-2">Gender</h4>
            <div id="chart-gender" style="height:240px"></div>
        </div>
        <div>
            <h4 class="mb-2">Age</h4>
            <div id="chart-age" style="height:240px"></div>
        </div>
    </div>
    <div id="reports-data"
         data-visitors='@json(array_values($data['visitors'] ?? []))'
         data-visitors-labels='@json(array_keys($data['visitors'] ?? []))'
         data-gender='@json(array_values($data['gender'] ?? []))'
         data-gender-labels='@json(array_keys($data['gender'] ?? []))'
         data-age='@json(array_values($data['age'] ?? []))'
         data-age-labels='@json(array_keys($data['age'] ?? []))'
    ></div>

    <script>
        document.addEventListener('livewire:load', function () {
            function drawCharts() {
                const el = document.getElementById('reports-data');
                const visitors = JSON.parse(el.dataset.visitors || '[]');
                const labels = JSON.parse(el.dataset.visitorsLabels || '[]');
                const genderSeries = JSON.parse(el.dataset.gender || '[]');
                const genderLabels = JSON.parse(el.dataset.genderLabels || '[]');
                const ageSeries = JSON.parse(el.dataset.age || '[]');
                const ageLabels = JSON.parse(el.dataset.ageLabels || '[]');

                if (window.ApexCharts) {
                    new ApexCharts(document.querySelector('#chart-visitors'), {
                        chart: { type: 'area', height: 320 },
                        series: [{ name: 'Visitors', data: visitors }],
                        xaxis: { categories: labels }
                    }).render();

                    new ApexCharts(document.querySelector('#chart-gender'), {
                        chart: { type: 'pie', height: 240 },
                        series: genderSeries,
                        labels: genderLabels
                    }).render();

                    new ApexCharts(document.querySelector('#chart-age'), {
                        chart: { type: 'bar', height: 240 },
                        series: [{ name: 'Count', data: ageSeries }],
                        xaxis: { categories: ageLabels }
                    }).render();
                }
            }

            drawCharts();

            Livewire.hook('message.processed', (message, component) => {
                // Update the data attributes with new values from server-rendered DOM
                const serverEl = document.getElementById('reports-data');
                if (serverEl) {
                    document.getElementById('reports-data').dataset.visitors = serverEl.dataset.visitors || '[]';
                    document.getElementById('reports-data').dataset.visitorsLabels = serverEl.dataset.visitorsLabels || '[]';
                    document.getElementById('reports-data').dataset.gender = serverEl.dataset.gender || '[]';
                    document.getElementById('reports-data').dataset.genderLabels = serverEl.dataset.genderLabels || '[]';
                    document.getElementById('reports-data').dataset.age = serverEl.dataset.age || '[]';
                    document.getElementById('reports-data').dataset.ageLabels = serverEl.dataset.ageLabels || '[]';
                }
                drawCharts();
            });
        });
    </script>
</div>
