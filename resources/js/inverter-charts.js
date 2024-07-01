import { Chart } from "chart.js/auto";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";

export default (
    livewire = Livewire.getByName('inverters.inverter-charts')[0],
    initialElementId = 'inverter-chart'
) => ({
    elementName: initialElementId,
    defaultOptions: {
        responsive: true,
        maintainAspectRatio: true,
        elements: {
            bar: {
                backgroundColor: '#eab308'
            },
            line: {
                fill: true
            },
            point: {
                pointStyle: false
            }
        }
    },
    selectedYear: livewire.entangle('selectedYear'),
    selectedMonth: livewire.entangle('selectedMonth'),
    selectedDay: livewire.entangle('selectedDay'),
    error: null,

    init() {
        Chart.defaults.borderColor = '#9ca3af20';
        Chart.defaults.color = '#9ca3af';

        if (this.selectedYear && this.selectedMonth && this.selectedDay) {
            this.createStatusChartForDay();
        } else if (this.selectedYear && this.selectedMonth) {
            this.createDailyOutputChartForMonth();
        } else if (this.selectedYear) {
            this.createMonthlyOutputChartForYear();
        }
    },

    setYear(year) {
        this.selectedYear = year;
        this.selectedMonth = null;
        this.selectedDay = null;
        this.createMonthlyOutputChartForYear();
    },

    isYearSelected(year) {
        return this.selectedYear === year;
    },

    setMonth(month) {
        this.selectedMonth = month;
        this.selectedDay = null;
        this.createDailyOutputChartForMonth();
    },

    isMonthSelected(month) {
        return this.selectedMonth === month;
    },

    setDay(day) {
        this.selectedDay = day;
        this.createStatusChartForDay();
    },

    isDaySelected(day) {
        return this.selectedDay === day;
    },

    createChart(type, options, data) {
        window.inverterChart = new Chart(
            document.getElementById(this.elementName),
            {
                type: type,
                options: { ...options, ...this.defaultOptions },
                data: data
            }
        );
    },

    destroyChart() {
        window.inverterChart?.destroy();
    },

    createChartFromResponse(response, type = 'bar', options = null) {
        if (response['status'] === '200' && response['data']) {
            this.error = null;
            this.destroyChart();
            this.createChart(type, response['options'] ?? null, response['data']);
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

    async createStatusChartForDay() {
        const response = await livewire.getStatusForDay();

        this.createChartFromResponse(response, 'line');
    },
});
