import { Chart } from "chart.js/auto";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";

export default (
    livewire = Livewire.getByName('inverters.inverter-show')[0],
    initialElementId = 'inverter-chart'
) => ({
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
    selectedYear: livewire.entangle('selectedYear'),
    selectedMonth: livewire.entangle('selectedMonth'),
    error: null,

    init() {
        Chart.defaults.borderColor = '#9ca3af20';
        Chart.defaults.color = '#9ca3af';

        if (this.selectedYear && this.selectedMonth) {
            this.createDailyOutputChartForMonth();
        } else if (this.selectedYear) {
            this.createMonthlyOutputChartForYear();
        }
    },

    setYear(year) {
        this.selectedYear = year;
        this.selectedMonth = null;
        this.createMonthlyOutputChartForYear();
    },

    isYearSelected(year) {
        return this.selectedYear === year;
    },

    setMonth(month) {
        this.selectedMonth = month;
        this.createDailyOutputChartForMonth();
    },

    isMonthSelected(month) {
        return this.selectedMonth === month;
    },

    createChart(data) {
        this.error = null;
        this.destroyChart();
        window.inverterChart = new Chart(
            document.getElementById(this.elementName),
            {
                type: 'bar',
                options: this.defaultOptions,
                data: data
            }
        );
    },

    destroyChart() {
        window.inverterChart?.destroy();
    },

    createChartFromResponse(response) {
        if (response['status'] === '200' && response['data']) {
            this.createChart(response['data']);
        } else {
            this.destroyChart();
            this.error = response['message'] ?? 'Error';
        }
    },

    async createMonthlyOutputChartForYear() {
        const response = await livewire.getMonthlyOutputForYear();

        this.createChartFromResponse(response);
    },

    async createDailyOutputChartForMonth() {
        const response = await livewire.getDailyOutputForMonth();

        this.createChartFromResponse(response);
    },
});
