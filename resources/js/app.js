import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import './bootstrap';
import inverterCharts from './inverter-charts';

Alpine.data('inverterCharts', inverterCharts);
Livewire.start();
