import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import './bootstrap';
import inverterCharts from './inverter-charts';

Alpine.data('inverterCharts', inverterCharts);

Livewire.hook('request', ({ fail }) => {
    fail(({ status, preventDefault }) => {
        if (status !== 419) {
            return;
        }

        preventDefault();
        window.location.reload();
    });
});

Livewire.start();
