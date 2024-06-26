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
    selectedYear: new Date().getFullYear(),
    selectedMonth: null,

    init() {
        Chart.defaults.borderColor = '#9ca3af20';
        Chart.defaults.color = '#9ca3af';
        this.createMonthlyOutputChartForYear(this.selectedYear);
    },

    livewire() {
        return Livewire.find(this.$el.closest('[wire\\:id]')?.getAttribute('wire:id')) ?? null;
    },

    setYear(year) {
        this.selectedYear = year;
        this.selectMonth = null;
        this.createMonthlyOutputChartForYear(year);
    },

    isYearSelected(year) {
        return this.selectedYear === year;
    },

    setMonth(year, month) {
        this.selectedYear = year;
        this.selectMonth = month;
        this.createDailyOutputChartForMonth(year, month);
    },

    isMonthSelected(year, month) {
        return this.selectedYear === year && this.selectMonth === month;
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

    createChartFromResponse(response) {
        const chartData = {
            labels: response.dataset.map(row => row.label),
            datasets: [
                {
                    label: response.dataset_label,
                    data: response.dataset.map(row => row.data)
                }
            ]
        };

        this.createChart(chartData);
    },

    async createMonthlyOutputChartForYear(year) {
        const response = await this.livewire().getMonthlyOutputForYear(year);
        this.createChartFromResponse(response);
    },

    async createDailyOutputChartForMonth(year, month) {
        const response = await this.livewire().getDailyOutputForMonth(year, month);
        this.createChartFromResponse(response);
    },
});
