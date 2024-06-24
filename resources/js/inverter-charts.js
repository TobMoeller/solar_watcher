import { Chart } from "chart.js/auto";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";

export default (initialElementId = 'inverter-chart') => ({
    elementName: initialElementId,
    defaultOptions: {
        responsive: true,
        maintainAspectRatio: true,
        elements: {
            bar: {
                backgroundColor: '#eab308'
            }
        }
    },
    init() {
        Chart.defaults.borderColor = '#9ca3af20';
        Chart.defaults.color = '#9ca3af';
        this.createMonthlyOutputChartForYear(new Date().getFullYear());
    },
    livewire() {
        return Livewire.find(this.$el.closest('[wire\\:id]')?.getAttribute('wire:id')) ?? null;
    },
    async createMonthlyOutputChartForYear(year) {
        const data = await this.livewire().getMonthlyOutputForYear(year);

        const chartData = {
            labels: data.map(row => row.month),
            datasets: [
                {
                    label: 'Output in kWh for: ' + year,
                    data: data.map(row => row.output)
                }
            ]
        };

        this.createChart(chartData);
    },
    createChart(data) {
        window.inverterChart?.destroy();
        window.inverterChart = new Chart(
            document.getElementById(this.elementName),
            {
                type: 'bar',
                options: this.defaultOptions,
                data: data
            }
        );
    },
});
